<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\NotificationChannel;
use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $businessName = config('app.name', 'Our Business');

        $headerHtml = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #2563eb; color: #ffffff; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .body { padding: 32px; color: #374151; line-height: 1.6; }
        .footer { background-color: #f9fafb; padding: 16px; text-align: center; color: #9ca3af; font-size: 12px; }
        .button { display: inline-block; background-color: #2563eb; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{$businessName}</h1>
    </div>
    <div class="body">
HTML;

        $footerHtml = <<<'HTML'
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</div>
</body>
</html>
HTML;

        $templates = [
            [
                'name' => 'Booking Created — Email',
                'event_trigger' => 'booking.created',
                'channel' => NotificationChannel::Email,
                'subject' => 'Your booking {{reference_number}} is confirmed',
                'body_html' => $headerHtml.<<<'HTML'
        <h2>Booking Confirmed!</h2>
        <p>Dear {{customer_name}},</p>
        <p>Your booking has been confirmed. Here are your booking details:</p>
        <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Reference</td><td style="padding:8px; border:1px solid #e5e7eb;">{{reference_number}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Resource</td><td style="padding:8px; border:1px solid #e5e7eb;">{{resource_name}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Check-in</td><td style="padding:8px; border:1px solid #e5e7eb;">{{check_in}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Check-out</td><td style="padding:8px; border:1px solid #e5e7eb;">{{check_out}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Total Amount</td><td style="padding:8px; border:1px solid #e5e7eb;">{{total_amount}}</td></tr>
        </table>
        <p>Thank you for choosing us!</p>
HTML
                .$footerHtml,
                'variables' => ['reference_number', 'customer_name', 'resource_name', 'check_in', 'check_out', 'total_amount'],
                'is_active' => true,
            ],
            [
                'name' => 'Booking Cancelled — Email',
                'event_trigger' => 'booking.cancelled',
                'channel' => NotificationChannel::Email,
                'subject' => 'Your booking {{reference_number}} has been cancelled',
                'body_html' => $headerHtml.<<<'HTML'
        <h2>Booking Cancelled</h2>
        <p>Dear {{customer_name}},</p>
        <p>We regret to inform you that your booking <strong>{{reference_number}}</strong> has been cancelled.</p>
        <p><strong>Reason:</strong> {{cancellation_reason}}</p>
        <p>If you have any questions, please contact us.</p>
HTML
                .$footerHtml,
                'variables' => ['reference_number', 'customer_name', 'cancellation_reason'],
                'is_active' => true,
            ],
            [
                'name' => 'Booking Reminder — Email',
                'event_trigger' => 'booking.reminder',
                'channel' => NotificationChannel::Email,
                'subject' => 'Reminder: Your booking {{reference_number}} is tomorrow',
                'body_html' => $headerHtml.<<<'HTML'
        <h2>Your Booking is Tomorrow!</h2>
        <p>Dear {{customer_name}},</p>
        <p>This is a friendly reminder that your booking is scheduled for tomorrow.</p>
        <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Reference</td><td style="padding:8px; border:1px solid #e5e7eb;">{{reference_number}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Resource</td><td style="padding:8px; border:1px solid #e5e7eb;">{{resource_name}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Check-in</td><td style="padding:8px; border:1px solid #e5e7eb;">{{check_in}}</td></tr>
        </table>
        <p>We look forward to seeing you!</p>
HTML
                .$footerHtml,
                'variables' => ['reference_number', 'customer_name', 'resource_name', 'check_in'],
                'is_active' => true,
            ],
            [
                'name' => 'Payment Received — Email',
                'event_trigger' => 'payment.received',
                'channel' => NotificationChannel::Email,
                'subject' => 'Payment received for booking {{reference_number}}',
                'body_html' => $headerHtml.<<<'HTML'
        <h2>Payment Received</h2>
        <p>Dear Customer,</p>
        <p>We have received your payment for booking <strong>{{reference_number}}</strong>.</p>
        <table style="width:100%; border-collapse: collapse; margin: 16px 0;">
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Amount</td><td style="padding:8px; border:1px solid #e5e7eb;">{{currency}} {{amount}}</td></tr>
            <tr><td style="padding:8px; border:1px solid #e5e7eb; font-weight:600;">Invoice #</td><td style="padding:8px; border:1px solid #e5e7eb;">{{invoice_number}}</td></tr>
        </table>
        <p>Thank you for your payment!</p>
HTML
                .$footerHtml,
                'variables' => ['reference_number', 'amount', 'currency', 'invoice_number'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::firstOrCreate(
                ['event_trigger' => $template['event_trigger'], 'channel' => $template['channel']->value],
                $template,
            );
        }
    }
}
