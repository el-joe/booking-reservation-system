<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tenant::query()->where('status', 'active');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('notes', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('booking_type')) {
            $query->where('business_type', $request->input('booking_type'));
        }

        $tenants = $query->paginate(12)->withQueryString();
        $bookingTypes = BookingType::cases();

        return view('central.website.search', compact('tenants', 'bookingTypes'));
    }
}
