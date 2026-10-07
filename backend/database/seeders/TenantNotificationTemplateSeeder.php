<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NotificationChannel;
use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class TenantNotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'event' => 'booking.created',
                'channel' => NotificationChannel::Email,
                'subject' => 'Booking Confirmation - {{reference_number}}',
                'body' => $this->bookingCreatedBody(),
                'is_active' => true,
            ],
            [
                'event' => 'booking.cancelled',
                'channel' => NotificationChannel::Email,
                'subject' => 'Booking Cancellation - {{reference_number}}',
                'body' => $this->bookingCancelledBody(),
                'is_active' => true,
            ],
            [
                'event' => 'booking.reminder',
                'channel' => NotificationChannel::Email,
                'subject' => 'Reminder: Your booking tomorrow',
                'body' => $this->bookingReminderBody(),
                'is_active' => true,
            ],
            [
                'event' => 'payment.received',
                'channel' => NotificationChannel::Email,
                'subject' => 'Payment Confirmed - {{invoice_number}}',
                'body' => $this->paymentReceivedBody(),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                [
                    'event' => $template['event'],
                    'channel' => $template['channel'],
                ],
                $template
            );
        }
    }

    private function bookingCreatedBody(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #2563eb; color: #ffffff; padding: 24px; text-align: center; }
        .body { padding: 32px; color: #374151; line-height: 1.6; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; color: #9ca3af; font-size: 12px; }
        .detail { margin: 8px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Booking Confirmed!</h1></div>
    <div class="body">
        <p>Dear {{customer_name}},</p>
        <p>Your booking has been confirmed. Here are your booking details:</p>
        <div class="detail"><span class="label">Reference:</span> {{reference_number}}</div>
        <div class="detail"><span class="label">Resource:</span> {{resource_name}}</div>
        <div class="detail"><span class="label">Check-in:</span> {{check_in}}</div>
        <div class="detail"><span class="label">Total Amount:</span> {{total_amount}}</div>
        <p>Thank you for your reservation!</p>
    </div>
    <div class="footer">This is an automated notification. Please do not reply to this email.</div>
</div>
</body>
</html>
HTML;
    }

    private function bookingCancelledBody(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Cancellation</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #dc2626; color: #ffffff; padding: 24px; text-align: center; }
        .body { padding: 32px; color: #374151; line-height: 1.6; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; color: #9ca3af; font-size: 12px; }
        .detail { margin: 8px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Booking Cancelled</h1></div>
    <div class="body">
        <p>Dear {{customer_name}},</p>
        <p>Your booking has been cancelled.</p>
        <div class="detail"><span class="label">Reference:</span> {{reference_number}}</div>
        <div class="detail"><span class="label">Resource:</span> {{resource_name}}</div>
        <div class="detail"><span class="label">Check-in:</span> {{check_in}}</div>
        <div class="detail"><span class="label">Total Amount:</span> {{total_amount}}</div>
        <p>If you have any questions, please contact our support team.</p>
    </div>
    <div class="footer">This is an automated notification. Please do not reply to this email.</div>
</div>
</body>
</html>
HTML;
    }

    private function bookingReminderBody(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #f59e0b; color: #ffffff; padding: 24px; text-align: center; }
        .body { padding: 32px; color: #374151; line-height: 1.6; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; color: #9ca3af; font-size: 12px; }
        .detail { margin: 8px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Booking Reminder</h1></div>
    <div class="body">
        <p>Dear {{customer_name}},</p>
        <p>This is a friendly reminder that you have a booking tomorrow.</p>
        <div class="detail"><span class="label">Reference:</span> {{reference_number}}</div>
        <div class="detail"><span class="label">Resource:</span> {{resource_name}}</div>
        <div class="detail"><span class="label">Check-in:</span> {{check_in}}</div>
        <div class="detail"><span class="label">Total Amount:</span> {{total_amount}}</div>
        <p>We look forward to seeing you!</p>
    </div>
    <div class="footer">This is an automated notification. Please do not reply to this email.</div>
</div>
</body>
</html>
HTML;
    }

    private function paymentReceivedBody(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #16a34a; color: #ffffff; padding: 24px; text-align: center; }
        .body { padding: 32px; color: #374151; line-height: 1.6; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; color: #9ca3af; font-size: 12px; }
        .detail { margin: 8px 0; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Payment Confirmed</h1></div>
    <div class="body">
        <p>Dear {{customer_name}},</p>
        <p>We have received your payment. Here are the details:</p>
        <div class="detail"><span class="label">Invoice Number:</span> {{invoice_number}}</div>
        <div class="detail"><span class="label">Reference:</span> {{reference_number}}</div>
        <div class="detail"><span class="label">Resource:</span> {{resource_name}}</div>
        <div class="detail"><span class="label">Check-in:</span> {{check_in}}</div>
        <div class="detail"><span class="label">Amount Paid:</span> {{total_amount}}</div>
        <p>Thank you for your payment!</p>
    </div>
    <div class="footer">This is an automated notification. Please do not reply to this email.</div>
</div>
</body>
</html>
HTML;
    }
}
