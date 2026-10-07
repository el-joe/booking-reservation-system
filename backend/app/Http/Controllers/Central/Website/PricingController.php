<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\Central\SeoService;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(SeoService $seo): View
    {
        $seo->setTitle('Pricing Plans — '.config('app.name', 'BookEase'))
            ->setDescription('Simple, transparent pricing for every stage of your business. Choose monthly or yearly and save 20%.')
            ->setCanonical(url('/pricing'));

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('central.website.pricing', compact('plans'));
    }
}
