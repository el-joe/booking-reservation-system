<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantWelcomeNotification extends Notification
{
    public function __construct(public readonly Tenant $tenant) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = 'http://'.($this->tenant->domains()->first()?->domain ?? '').'/dashboard';
        $supportEmail = config('mail.from.address', 'support@bookings.app');

        return (new MailMessage)
            ->subject('Welcome to '.config('app.name').' — Your Account is Ready')
            ->greeting('Welcome, '.$this->tenant->contact_name.'!')
            ->line('Your business "'.$this->tenant->name.'" has been successfully registered on '.config('app.name').'.')
            ->line('You can now log in to your dashboard and start managing your bookings.')
            ->action('Go to Dashboard', $loginUrl)
            ->line('If you have any questions, please contact us at '.$supportEmail.'.')
            ->salutation('The '.config('app.name').' Team');
    }
}
