@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'hidden']) }} data-page-feedback data-feedback-level="success">
        {{ $status }}
    </div>
@endif
