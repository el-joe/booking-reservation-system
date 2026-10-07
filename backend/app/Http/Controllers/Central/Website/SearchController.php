<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $bookingTypes = BookingType::cases();

        $query = Tenant::query()->where('status', TenantStatus::Active);

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('booking_type')) {
            $query->where('business_type', $request->input('booking_type'));
        }

        $tenants = $query->paginate(12)->withQueryString();

        return view('central.website.search', compact('bookingTypes', 'tenants'));
    }
}
