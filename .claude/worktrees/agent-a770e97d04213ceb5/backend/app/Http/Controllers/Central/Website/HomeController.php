<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredTenants = Tenant::query()
            ->where('status', 'active')
            ->limit(8)
            ->get();

        $categories = BookingType::cases();

        $categoryCounts = collect(BookingType::cases())->mapWithKeys(function (BookingType $type) {
            return [$type->value => Tenant::query()->where('business_type', $type->value)->where('status', 'active')->count()];
        });

        return view('central.website.home', compact('featuredTenants', 'categories', 'categoryCounts'));
    }
}
