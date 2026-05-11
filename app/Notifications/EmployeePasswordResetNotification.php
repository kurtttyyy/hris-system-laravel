<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EmployeePasswordResetNotification extends Notification
{
    public function __construct(private readonly string $token)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Password Reset Link')
            ->view('email.employee-password-reset-link', [
                'employee' => $notifiable,
                'resetUrl' => $resetUrl,
                'expiresAt' => Carbon::now()->addMinutes((int) config('auth.passwords.users.expire', 60)),
            ]);
    }
}
