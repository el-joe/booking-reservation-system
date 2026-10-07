<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Central\SeoService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setTitle(config('app.name', 'BookEase').' — Book Anything, Anywhere')
            ->setDescription('The leading SaaS booking platform. Discover hotels, restaurants, services, tours and more from thousands of businesses.')
            ->setCanonical(url('/'))
            ->setSchema([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => config('app.name', 'BookEase'),
                'url' => url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/search').'?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ]);

        $categories = BookingType::cases();

        $categoryCounts = Tenant::query()
            ->where('status', TenantStatus::Active)
            ->selectRaw('business_type, COUNT(*) as count')
            ->groupBy('business_type')
            ->pluck('count', 'business_type')
            ->toArray();

        $featuredTenants = Tenant::query()
            ->where('status', TenantStatus::Active)
            ->latest()
            ->limit(8)
            ->get();

        $stats = [
            'tenants' => Tenant::where('status', TenantStatus::Active)->count(),
            'categories' => count(BookingType::cases()),
        ];

        $latestPosts = BlogPost::published()->latest('published_at')->limit(3)->get();

        $featuredPlan = Plan::where('is_featured', true)->where('is_active', true)->first()
            ?? Plan::where('is_active', true)->orderBy('sort_order')->skip(1)->first();

        return view('central.website.home', compact(
            'categories', 'categoryCounts', 'featuredTenants', 'stats', 'latestPosts', 'featuredPlan'
        ));
    }
}
