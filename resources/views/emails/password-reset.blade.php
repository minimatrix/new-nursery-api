@component('mail::message')
    # Reset Your Password

    You are receiving this email because we received a password reset request for your account.

    Your password reset token is: {{ $resetToken }}

    This token will expire in 1 hour.

    If you did not request a password reset, no further action is required.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
