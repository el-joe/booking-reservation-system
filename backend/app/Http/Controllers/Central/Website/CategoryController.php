<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $bookingType = BookingType::from($slug);

        $tenants = Tenant::query()
            ->where('status', TenantStatus::Active)
            ->where('business_type', $bookingType->value)
            ->paginate(12);

        return view('central.website.category', compact('bookingType', 'tenants'));
    }
}
