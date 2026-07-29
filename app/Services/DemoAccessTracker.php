<?php

namespace App\Services;

use App\Models\DemoAccessHistory;
use App\Models\DemoAccessSession;
use App\Models\DemoIpAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemoAccessTracker
{
    private const SESSION_KEY = 'demo_access_session_id';

    public function ensureSession(Request $request): DemoAccessSession
    {
        $session = DemoAccessSession::find($request->session()->get(self::SESSION_KEY));

        return $session ?: $this->startSession($request);
    }

    public function startSession(Request $request): DemoAccessSession
    {
        $now = now();
        $metadata = $this->metadata($request);
        $sessionHash = hash_hmac('sha256', $request->session()->getId(), (string) config('app.key'));

        $session = DB::transaction(function () use ($request, $now, $metadata, $sessionHash) {
            $ip = DemoIpAddress::query()->where('ip_address', $request->ip())->lockForUpdate()->first();

            if (! $ip) {
                $ip = DemoIpAddress::create([
                    'ip_address' => $request->ip(),
                    'first_access_at' => $now,
                    'last_access_at' => $now,
                    'last_active_at' => $now,
                    'visit_count' => 0,
                    ...$metadata,
                ]);
            }

            $session = DemoAccessSession::firstOrCreate(
                ['session_hash' => $sessionHash],
                [
                    'demo_ip_address_id' => $ip->id,
                    'started_at' => $now,
                    'last_activity_at' => $now,
                    'entry_page' => $this->page($request),
                ]
            );

            if ($session->wasRecentlyCreated) {
                $ip->increment('visit_count');
            }

            $ip->forceFill([
                ...$this->profileUpdates($metadata),
                'last_access_at' => $now,
                'last_active_at' => $now,
            ])->save();

            return $session;
        });

        $request->session()->put(self::SESSION_KEY, $session->id);

        return $session;
    }

    public function recordPage(Request $request): void
    {
        $session = $this->ensureSession($request);

        $now = now();
        $page = $this->page($request);
        $metadata = $this->metadata($request);

        DB::transaction(function () use ($request, $session, $now, $page, $metadata) {
            $session->forceFill(['last_activity_at' => $now])->save();
            $session->ipAddress()->update([
                ...$this->profileUpdates($metadata),
                'last_access_at' => $now,
                'last_active_at' => $now,
                'last_page' => $page,
            ]);

            DemoAccessHistory::create([
                'demo_ip_address_id' => $session->demo_ip_address_id,
                'demo_access_session_id' => $session->id,
                'accessed_at' => $now,
                'page' => $page,
                'route_name' => $request->route()?->getName(),
            ]);
        });
    }

    /** @return array{browser: string, device_type: string, operating_system: string, country: ?string, city: ?string} */
    private function metadata(Request $request): array
    {
        $agent = (string) $request->userAgent();

        return [
            'browser' => match (true) {
                str_contains($agent, 'Edg/') => 'Microsoft Edge',
                str_contains($agent, 'OPR/') || str_contains($agent, 'Opera') => 'Opera',
                str_contains($agent, 'Chrome/') || str_contains($agent, 'CriOS/') => 'Chrome',
                str_contains($agent, 'Firefox/') || str_contains($agent, 'FxiOS/') => 'Firefox',
                str_contains($agent, 'Safari/') => 'Safari',
                default => 'Unknown',
            },
            'device_type' => match (true) {
                preg_match('/iPad|Tablet|PlayBook|Silk/i', $agent) === 1 => 'Tablet',
                preg_match('/Mobile|Android|iPhone|iPod/i', $agent) === 1 => 'Mobile',
                default => 'Desktop',
            },
            'operating_system' => match (true) {
                str_contains($agent, 'Windows') => 'Windows',
                preg_match('/iPhone|iPad|iPod/', $agent) === 1 => 'iOS',
                str_contains($agent, 'Android') => 'Android',
                str_contains($agent, 'Mac OS X') || str_contains($agent, 'Macintosh') => 'macOS',
                str_contains($agent, 'Linux') => 'Linux',
                default => 'Unknown',
            },
            // Location is accepted only when infrastructure already supplies it; no third-party lookup is made.
            'country' => $this->cleanLocation($request->header('CF-IPCountry') ?? $request->header('X-Country-Name')),
            'city' => $this->cleanLocation($request->header('X-Vercel-IP-City') ?? $request->header('X-City')),
        ];
    }

    private function cleanLocation(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return mb_substr(rawurldecode(trim($value)), 0, 100);
    }

    private function profileUpdates(array $metadata): array
    {
        return array_filter(
            $metadata,
            fn (mixed $value, string $key) => ! in_array($key, ['country', 'city'], true) || $value !== null,
            ARRAY_FILTER_USE_BOTH,
        );
    }

    private function page(Request $request): string
    {
        return '/'.ltrim($request->path(), '/');
    }
}
