<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // General
            [
                'question' => 'What is BookEase?',
                'answer' => 'BookEase is a cloud-based booking and scheduling platform designed for service businesses of all sizes. It allows you to accept online bookings, manage your schedule, process payments, and communicate with clients — all from one easy-to-use dashboard.',
                'category' => 'General',
                'sort_order' => 1,
            ],
            [
                'question' => 'How do I get started?',
                'answer' => 'Getting started with BookEase is simple. Sign up for a free trial, follow our onboarding wizard to set up your business profile, add your services and availability, and share your unique booking link with clients. You can have your first booking page live in under 10 minutes.',
                'category' => 'General',
                'sort_order' => 2,
            ],
            [
                'question' => 'What types of businesses can use BookEase?',
                'answer' => 'BookEase is designed for any service business that relies on appointments or reservations. This includes salons, spas, fitness studios, healthcare providers, consultants, tutors, photographers, event venues, and many more. If your business books time with clients, BookEase can help.',
                'category' => 'General',
                'sort_order' => 3,
            ],
            [
                'question' => 'Is there a free trial?',
                'answer' => 'Yes! We offer a 14-day free trial on all plans with no credit card required. You will have access to all features during the trial period so you can fully evaluate whether BookEase is the right fit for your business.',
                'category' => 'General',
                'sort_order' => 4,
            ],
            [
                'question' => 'How do I contact support?',
                'answer' => 'Our support team is available via live chat, email, and phone. Starter plan customers have access to email support during business hours. Growth and Enterprise customers enjoy priority support with faster response times. Visit our Help Center for instant answers to common questions.',
                'category' => 'General',
                'sort_order' => 5,
            ],

            // Billing
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit and debit cards including Visa, Mastercard, and American Express. For Enterprise plans, we also offer bank transfer and purchase order options. All payments are processed securely through our PCI-compliant payment gateway.',
                'category' => 'Billing',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can I change my plan?',
                'answer' => 'Absolutely. You can upgrade or downgrade your plan at any time from your account settings. Upgrades take effect immediately, with the cost prorated for the remainder of your billing cycle. Downgrades take effect at the start of your next billing cycle.',
                'category' => 'Billing',
                'sort_order' => 2,
            ],
            [
                'question' => 'Do you offer refunds?',
                'answer' => 'We offer a 30-day money-back guarantee for new subscriptions. If you are not satisfied with BookEase within the first 30 days of your paid subscription, contact our support team and we will process a full refund, no questions asked.',
                'category' => 'Billing',
                'sort_order' => 3,
            ],
            [
                'question' => 'What happens when I exceed my booking limit?',
                'answer' => 'If you approach your monthly booking limit, we will notify you so you can upgrade before hitting the cap. Once you reach the limit, new online bookings will be paused until you upgrade your plan or the next billing cycle begins. Existing bookings are never affected.',
                'category' => 'Billing',
                'sort_order' => 4,
            ],
            [
                'question' => 'Is there a setup fee?',
                'answer' => 'No, there are no setup fees on any BookEase plan. You pay only the monthly or annual subscription price. We believe in transparent pricing with no hidden costs.',
                'category' => 'Billing',
                'sort_order' => 5,
            ],

            // Technical
            [
                'question' => 'Do I need technical skills to use BookEase?',
                'answer' => 'Not at all. BookEase is designed to be user-friendly for non-technical business owners. Our intuitive interface and guided setup process mean you can configure your entire booking system without writing a single line of code. We also offer onboarding sessions for new customers who want personalized guidance.',
                'category' => 'Technical',
                'sort_order' => 1,
            ],
            [
                'question' => 'Can I use my own domain?',
                'answer' => 'Yes, Growth and Enterprise plan customers can connect a custom domain to their BookEase booking page. This means clients will see your booking page at a URL like booking.yourbusiness.com rather than the default BookEase subdomain, creating a more professional branded experience.',
                'category' => 'Technical',
                'sort_order' => 2,
            ],
            [
                'question' => 'Does BookEase integrate with other tools?',
                'answer' => 'BookEase integrates with a wide range of popular business tools including Google Calendar, Outlook, Zoom, Stripe, PayPal, Mailchimp, and Zapier. Our API is available on Growth and Enterprise plans, allowing developers to build custom integrations with virtually any software.',
                'category' => 'Technical',
                'sort_order' => 3,
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'Security is our top priority. BookEase uses bank-level 256-bit SSL encryption for all data transmission, stores data in ISO 27001-certified data centers, and undergoes regular third-party security audits. We are fully GDPR compliant and provide data processing agreements for all customers in applicable regions.',
                'category' => 'Technical',
                'sort_order' => 4,
            ],
            [
                'question' => 'Can I export my data?',
                'answer' => 'Yes, you own your data and can export it at any time. BookEase allows you to export your client list, booking history, and financial reports in CSV format. Enterprise customers have access to additional export formats and scheduled automated exports via our API.',
                'category' => 'Technical',
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question'], 'category' => $faq['category']],
                array_merge($faq, ['is_active' => true])
            );
        }
    }
}
