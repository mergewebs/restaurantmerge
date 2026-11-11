@php
    $notificationStatuses = [
        'profile-updated' => __('Profile information updated successfully.'),
        'passcode-updated' => __('Passcode updated successfully.'),
    ];
@endphp
<div hidden data-notification-feedback>
    @foreach (['success' => 'success', 'contact_success' => 'success', 'error' => 'error', 'warning' => 'warning', 'info' => 'info', 'status' => 'success', 'demo_action_blocked' => 'info'] as $key => $level)
        @if (is_string(session($key)) && session($key) !== '')
            <span data-page-feedback data-feedback-level="{{ $level }}">{{ $key === 'status' ? ($notificationStatuses[session($key)] ?? session($key)) : session($key) }}</span>
        @endif
    @endforeach
    @foreach (collect($errors->getBags())->flatMap(fn ($bag) => $bag->all())->unique() as $message)
        <span data-page-feedback data-feedback-level="error">{{ $message }}</span>
    @endforeach
</div>
