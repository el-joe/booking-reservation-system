<?php

namespace App\Services\Central;

use App\Enums\TenantStatus;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', TenantStatus::Active)->count();
        $suspendedTenants = Tenant::where('status', TenantStatus::Suspended)->count();
        $trialTenants = Tenant::where('status', TenantStatus::Trial)->count();

        $mrr = Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        $totalPlans = Plan::where('is_active', true)->count();

        $blogPostsCount = class_exists(BlogPost::class) ? BlogPost::published()->count() : 0;
        $unreadContacts = class_exists(ContactMessage::class) ? ContactMessage::unread()->count() : 0;
        $faqsCount = class_exists(Faq::class) ? Faq::active()->count() : 0;

        return [
            'total_tenants' => $totalTenants,
            'active_tenants' => $activeTenants,
            'suspended_tenants' => $suspendedTenants,
            'trial_tenants' => $trialTenants,
            'mrr' => (float) $mrr,
            'total_plans' => $totalPlans,
            'blog_posts_count' => $blogPostsCount,
            'unread_contacts' => $unreadContacts,
            'faqs_count' => $faqsCount,
        ];
    }

    public function getTenantGrowthChart(int $months = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $data[] = Tenant::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getRevenueChart(int $months = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $revenue = Subscription::where('status', 'active')
                ->whereYear('subscriptions.created_at', $date->year)
                ->whereMonth('subscriptions.created_at', $date->month)
                ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                ->sum('plans.price');
            $data[] = (float) $revenue;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function getRecentTenants(int $limit = 10): Collection
    {
        return Tenant::with(['subscription.plan'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function getPlanDistribution(): array
    {
        $distribution = Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->select('plans.name', DB::raw('count(*) as count'))
            ->groupBy('plans.id', 'plans.name')
            ->get();

        return [
            'labels' => $distribution->pluck('name')->toArray(),
            'data' => $distribution->pluck('count')->toArray(),
        ];
    }
}
