<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => '10 Ways to Grow Your Booking Business Online',
                'category' => 'Business Tips',
                'excerpt' => 'Growing a booking business in the digital age requires smart strategies and the right tools. Discover ten proven methods that successful business owners use to attract more clients and increase revenue.',
                'body' => "In today's competitive market, having an online booking presence is no longer optional — it's essential. Businesses that embrace digital booking tools consistently outperform those that rely on phone calls and manual scheduling. The first step is ensuring your booking page is mobile-friendly and loads quickly, as over 60% of bookings now happen on smartphones.\n\nSocial media is one of the most powerful channels for driving traffic to your booking page. By sharing client success stories, behind-the-scenes content, and promotional offers on platforms like Instagram and Facebook, you can build a loyal following that converts to repeat customers. Consider running targeted ad campaigns during your slow seasons to fill gaps in your schedule.\n\nEmail marketing remains one of the highest-ROI channels available to small businesses. Build a list of past and prospective clients and send regular newsletters featuring booking tips, seasonal promotions, and business updates. Automated reminders and follow-up sequences can dramatically reduce no-shows and encourage repeat bookings.\n\nFinally, leverage the power of customer reviews. Encourage satisfied clients to leave reviews on Google and your booking page. Positive social proof is one of the strongest conversion drivers available, and businesses with a strong review profile consistently book more appointments than those without.",
                'published_at' => Carbon::now()->subDays(15),
            ],
            [
                'title' => 'How to Set Up Your First Booking Page in Minutes',
                'category' => 'Getting Started',
                'excerpt' => 'Setting up an online booking page has never been easier. This step-by-step guide walks you through everything you need to get your first bookings rolling in within minutes of signing up.',
                'body' => "Creating your first booking page with BookEase is a straightforward process that takes just a few minutes. After signing up, you'll be guided through our intuitive onboarding flow that helps you configure your business profile, services, and availability. You don't need any technical skills — if you can send an email, you can set up a professional booking page.\n\nStart by defining the services you offer. Each service should have a clear name, description, duration, and price. Being transparent about what clients receive helps set expectations and reduces last-minute cancellations. You can also add buffer times between appointments to give yourself breathing room for preparation and cleanup.\n\nNext, configure your availability. BookEase allows you to set regular working hours, block out holidays, and define custom availability for specific services. You can also connect your Google or Outlook calendar to automatically prevent double-bookings based on your existing commitments.\n\nOnce your page is live, share the booking link everywhere — your website, social media profiles, email signature, and even your business cards. The easier you make it for clients to book, the more bookings you'll receive. Track your performance through the analytics dashboard and refine your offerings based on what's most popular.",
                'published_at' => Carbon::now()->subDays(30),
            ],
            [
                'title' => 'Why Online Booking Is Essential for Modern Businesses',
                'category' => 'Industry Insights',
                'excerpt' => 'Consumer expectations have shifted dramatically in the past decade. Learn why businesses without online booking are losing clients to competitors who offer the convenience modern customers demand.',
                'body' => "The way consumers make purchasing decisions has fundamentally changed. Today's customers expect instant gratification — they want to research a service, check availability, and book an appointment in one seamless digital experience. Businesses that still require phone calls during business hours are creating unnecessary friction that drives potential clients to competitors.\n\nStudies show that 40% of online bookings are made outside of business hours. This means that if you're relying solely on phone bookings, you're potentially missing nearly half of your available business. An online booking system that accepts appointments 24/7 acts like a salesperson that never sleeps, capturing revenue even while you're resting.\n\nCustomer expectations around convenience have been further elevated by the rise of on-demand services. Consumers who can summon a ride, order groceries, or stream any movie in seconds expect the same ease from their service providers. A clunky booking process signals that your business may be similarly outdated in other areas.\n\nAdopting online booking also improves your operational efficiency significantly. Automated confirmations, reminders, and follow-ups reduce the administrative burden on your team, allowing them to focus on delivering exceptional service rather than managing a phone queue. The data collected through digital bookings also provides valuable insights into your busiest periods, most popular services, and client retention rates.",
                'published_at' => Carbon::now()->subDays(45),
            ],
            [
                'title' => 'Maximizing Revenue with Smart Scheduling',
                'category' => 'Business Tips',
                'excerpt' => 'Smart scheduling is about more than filling your calendar. Discover advanced strategies for optimizing your pricing, managing peak demand, and reducing revenue-killing gaps in your schedule.',
                'body' => "Revenue optimization in a booking-based business starts with understanding your demand patterns. By analyzing which time slots fill fastest and which remain consistently empty, you can make informed decisions about pricing, staffing, and promotional strategies. BookEase's analytics dashboard provides this visibility at a glance, empowering data-driven decisions.\n\nDynamic pricing is a powerful tool that many service businesses overlook. By offering lower prices during off-peak hours and premium rates during high-demand periods, you can smooth out your schedule while increasing overall revenue. This approach, long used by airlines and hotels, is increasingly accessible to small businesses through modern booking platforms.\n\nReducing no-shows is another critical component of revenue maximization. Every unfilled slot represents lost revenue and wasted capacity. Implement a combination of automated reminders, deposit requirements for new clients, and clear cancellation policies to minimize last-minute gaps. Studies show that SMS reminders sent 24 hours before an appointment reduce no-shows by up to 40%.\n\nPackages and memberships are excellent tools for securing recurring revenue. By offering discounted rates for clients who purchase multiple sessions upfront or subscribe to a monthly membership, you create predictable cash flow and build stronger long-term relationships. BookEase's package management features make it easy to create, sell, and track these offerings.",
                'published_at' => Carbon::now()->subDays(60),
            ],
            [
                'title' => 'Customer Experience: How Seamless Booking Wins Loyalty',
                'category' => 'Industry Insights',
                'excerpt' => 'Customer loyalty is built at every touchpoint, including the booking process itself. Learn how investing in a seamless booking experience translates directly into repeat business and referrals.',
                'body' => "First impressions matter enormously in service businesses, and for many clients, the booking experience is their very first interaction with your brand. A frustrating, confusing, or slow booking process can cause potential clients to abandon ship before they've even experienced your service. Conversely, a smooth and intuitive booking flow sets a positive tone for the entire relationship.\n\nPersonalization is a key driver of customer loyalty. When clients return to your booking page and find their details pre-filled, see service recommendations based on their history, or receive personalized follow-ups acknowledging their specific needs, they feel valued rather than like just another transaction. BookEase stores client profiles that make this level of personalization automatic.\n\nCommunication throughout the booking lifecycle is equally important. Clients appreciate prompt confirmation emails that include all the details they need — the appointment time, location, what to bring, and how to reschedule if necessary. Post-appointment follow-ups that request feedback show clients that their experience matters to you and create natural opportunities to address any issues before they turn into negative reviews.\n\nLoyalty programs are another effective tool for turning one-time bookers into regulars. Simple points systems, referral rewards, or VIP pricing tiers give clients tangible reasons to choose you over competitors. The emotional connection created by feeling recognized and rewarded is a powerful retention force that no discount alone can replicate.",
                'published_at' => Carbon::now()->subDays(75),
            ],
            [
                'title' => 'Platform Update: New Features for 2025',
                'category' => 'Product Updates',
                'excerpt' => 'We have been busy building the features you asked for. This month we are rolling out several major updates that make managing your booking business faster, smarter, and more profitable.',
                'body' => "We have been listening closely to your feedback over the past year, and we are thrilled to announce a significant set of new features launching across the BookEase platform. These updates were built based on direct input from thousands of businesses like yours, and we believe they will make a meaningful difference in your day-to-day operations.\n\nFirst, we are introducing our new AI-powered scheduling assistant. This feature analyzes your historical booking patterns and automatically suggests optimal availability windows, pricing adjustments, and promotional timing. Early beta testers reported a 23% increase in bookings during their first month using the feature.\n\nWe are also launching a completely redesigned mobile app with offline mode. You can now manage your bookings, view client profiles, and process payments even without an internet connection — perfect for businesses that operate in locations with unreliable connectivity. Sync happens automatically when you reconnect.\n\nFinally, our new channel manager integration allows you to sync your availability across multiple booking platforms simultaneously. Whether clients book through your website, Google Business Profile, or social media, all appointments flow into a single unified calendar, eliminating double-bookings and manual reconciliation. We are starting with Google and Facebook integrations, with more platforms coming in Q2.",
                'published_at' => Carbon::now()->subDays(5),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                array_merge($post, [
                    'slug' => Str::slug($post['title']),
                    'author_name' => 'BookEase Team',
                    'status' => 'published',
                ])
            );
        }
    }
}
