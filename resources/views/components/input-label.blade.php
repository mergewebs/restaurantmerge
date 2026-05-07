@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-300 ml-1 mb-1']) }}>
    {{ $value ?? $slot }}
</label>
