<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ProductImageService
{
    public const DISK = 'product-images';
    public const DISPLAY_MAX_WIDTH = 1600;
    public const DISPLAY_MAX_HEIGHT = 1600;
    public const THUMBNAIL_SIZE = 600;
    private const DISPLAY_DIRECTORY = 'optimized';
    private const ORIGINAL_DIRECTORY = 'originals';
    private const THUMBNAIL_DIRECTORY = 'thumbnails';
    private const DISPLAY_QUALITY = 82;
    private const THUMBNAIL_QUALITY = 80;

    public function replaceUploadedImage(MenuItem $menuItem, UploadedFile $file): void
    {
        $this->deleteGeneratedImages($menuItem, deleteOriginal: true);

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $basename = $this->uploadedFileBaseName($file);
        $originalPath = self::ORIGINAL_DIRECTORY . '/' . $menuItem->getKey() . '/' . $basename . '.' . $extension;

        Storage::disk(self::DISK)->makeDirectory(self::ORIGINAL_DIRECTORY . '/' . $menuItem->getKey());
        Storage::disk(self::DISK)->put($originalPath, file_get_contents($file->getRealPath()));

        $menuItem->forceFill($this->buildImagePayload(
            $menuItem,
            Storage::disk(self::DISK)->path($originalPath),
            $originalPath,
        ))->save();
    }

    public function removeImage(MenuItem $menuItem): void
    {
        $this->deleteGeneratedImages($menuItem, deleteOriginal: true);

        $menuItem->forceFill([
            'image_path' => null,
            'image_display_path' => null,
            'image_thumbnail_path' => null,
        ])->save();
    }

    public function optimizeMenuItem(MenuItem $menuItem, bool $force = false): string
    {
        $sourcePath = $this->resolveSourcePath($menuItem);

        if ($sourcePath === null) {
            return 'missing';
        }

        if (! $force && $this->hasOptimizedImages($menuItem)) {
            return 'skipped';
        }

        $menuItem->forceFill($this->buildImagePayload(
            $menuItem,
            $sourcePath,
            $menuItem->image_path,
        ))->save();

        return 'optimized';
    }

    public function deleteGeneratedImages(MenuItem $menuItem, bool $deleteOriginal = false): void
    {
        $paths = [
            $menuItem->image_display_path,
            $menuItem->image_thumbnail_path,
        ];

        if ($deleteOriginal) {
            $paths[] = $menuItem->image_path;
        }

        collect($paths)
            ->filter(fn (?string $path) => filled($path))
            ->each(fn (string $path) => Storage::disk(self::DISK)->delete($path));
    }

    public function hasOptimizedImages(MenuItem $menuItem): bool
    {
        return filled($menuItem->image_display_path)
            && filled($menuItem->image_thumbnail_path)
            && Storage::disk(self::DISK)->exists($menuItem->image_display_path)
            && Storage::disk(self::DISK)->exists($menuItem->image_thumbnail_path);
    }

    private function buildImagePayload(MenuItem $menuItem, string $sourcePath, ?string $originalPath): array
    {
        ['image' => $sourceImage] = $this->loadImage($sourcePath);

        try {
            $basename = $this->imageBaseName($menuItem, $sourcePath);
            $displayDirectory = self::DISPLAY_DIRECTORY . '/' . $menuItem->getKey();
            $thumbnailDirectory = self::THUMBNAIL_DIRECTORY . '/' . $menuItem->getKey();
            $displayPath = $displayDirectory . '/' . $basename . '.webp';
            $thumbnailPath = $thumbnailDirectory . '/' . $basename . '.webp';

            Storage::disk(self::DISK)->makeDirectory($displayDirectory);
            Storage::disk(self::DISK)->makeDirectory($thumbnailDirectory);

            $displayImage = $this->resizeToFit($sourceImage, self::DISPLAY_MAX_WIDTH, self::DISPLAY_MAX_HEIGHT);
            $thumbnailImage = $this->createSquareThumbnail($sourceImage, self::THUMBNAIL_SIZE);

            $this->saveWebp($displayImage, Storage::disk(self::DISK)->path($displayPath), self::DISPLAY_QUALITY);
            $this->saveWebp($thumbnailImage, Storage::disk(self::DISK)->path($thumbnailPath), self::THUMBNAIL_QUALITY);

            imagedestroy($displayImage);
            imagedestroy($thumbnailImage);
        } finally {
            imagedestroy($sourceImage);
        }

        return [
            'image_path' => $originalPath,
            'image_display_path' => $displayPath,
            'image_thumbnail_path' => $thumbnailPath,
        ];
    }

    private function resolveSourcePath(MenuItem $menuItem): ?string
    {
        if (filled($menuItem->image_path) && Storage::disk(self::DISK)->exists($menuItem->image_path)) {
            return Storage::disk(self::DISK)->path($menuItem->image_path);
        }

        $legacyAssetPath = $menuItem->legacyImageAssetPath();

        if (blank($legacyAssetPath)) {
            return null;
        }

        $absolutePath = public_path($legacyAssetPath);

        return is_file($absolutePath) ? $absolutePath : null;
    }

    private function loadImage(string $path): array
    {
        $imageInfo = @getimagesize($path);

        if ($imageInfo === false) {
            throw new RuntimeException("Unable to read image information from [{$path}].");
        }

        $contents = file_get_contents($path);
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;

        if ($image === false) {
            throw new RuntimeException("Unable to create an image resource from [{$path}].");
        }

        $mime = $imageInfo['mime'] ?? null;
        $image = $this->applyOrientation($image, $path, $mime);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        return [
            'image' => $image,
            'mime' => $mime,
        ];
    }

    private function applyOrientation(\GdImage $image, string $path, ?string $mime): \GdImage
    {
        if ($mime !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        return match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function resizeToFit(\GdImage $sourceImage, int $maxWidth, int $maxHeight): \GdImage
    {
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight, 1);
        $targetWidth = max(1, (int) round($sourceWidth * $ratio));
        $targetHeight = max(1, (int) round($sourceHeight * $ratio));

        return $this->resampleImage($sourceImage, $targetWidth, $targetHeight);
    }

    private function createSquareThumbnail(\GdImage $sourceImage, int $size): \GdImage
    {
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $ratio = min($size / $sourceWidth, $size / $sourceHeight, 1);
        $targetWidth = max(1, (int) round($sourceWidth * $ratio));
        $targetHeight = max(1, (int) round($sourceHeight * $ratio));

        $thumbnail = imagecreatetruecolor($size, $size);

        if (! $thumbnail instanceof \GdImage) {
            throw new RuntimeException('Unable to create the thumbnail canvas.');
        }

        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
        $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
        imagefilledrectangle($thumbnail, 0, 0, $size, $size, $transparent);

        $offsetX = (int) floor(($size - $targetWidth) / 2);
        $offsetY = (int) floor(($size - $targetHeight) / 2);

        imagecopyresampled(
            $thumbnail,
            $sourceImage,
            $offsetX,
            $offsetY,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $thumbnail;
    }

    private function resampleImage(\GdImage $sourceImage, int $targetWidth, int $targetHeight): \GdImage
    {
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if (! $targetImage instanceof \GdImage) {
            throw new RuntimeException('Unable to create the resized image canvas.');
        }

        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
        imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            imagesx($sourceImage),
            imagesy($sourceImage),
        );

        return $targetImage;
    }

    private function saveWebp(\GdImage $image, string $path, int $quality): void
    {
        if (! function_exists('imagewebp') || ! imagewebp($image, $path, $quality)) {
            throw new RuntimeException("Unable to save optimized image [{$path}] as WebP.");
        }
    }

    private function imageBaseName(MenuItem $menuItem, string $sourcePath): string
    {
        $base = pathinfo($sourcePath, PATHINFO_FILENAME);

        if ($base === '') {
            $base = (string) $menuItem->name;
        }

        return $this->normalizeBaseName($base, (string) $menuItem->name);
    }

    private function uploadedFileBaseName(UploadedFile $file): string
    {
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $this->normalizeBaseName($base, 'product-image');
    }

    private function normalizeBaseName(string $preferred, string $fallback): string
    {
        $normalized = Str::slug($preferred);

        if ($normalized === '') {
            $normalized = Str::slug($fallback);
        }

        return $normalized !== '' ? $normalized : 'product-image';
    }
}
