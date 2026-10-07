<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\TenantStatus;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function show(string $slug): View
    {
        $tenant = Tenant::query()
            ->where('id', $slug)
            ->where('status', TenantStatus::Active)
            ->firstOrFail();

        return view('central.website.listing', compact('tenant'));
    }
}
