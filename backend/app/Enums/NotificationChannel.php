<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationChannel: string
{
    case Email = 'email';
    case SMS = 'sms';
    case Push = 'push';
    case WhatsApp = 'whatsapp';
    case Database = 'database';

    public function label(): string
    {
        return match ($this) {
            self::Email => 'Email',
            self::SMS => 'SMS',
            self::Push => 'Push Notification',
            self::WhatsApp => 'WhatsApp',
            self::Database => 'In-App',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Email => 'blue',
            self::SMS => 'green',
            self::Push => 'orange',
            self::WhatsApp => 'emerald',
            self::Database => 'gray',
        };
    }
}
