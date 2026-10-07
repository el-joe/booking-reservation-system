<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function show(Tenant $tenant): View
    {
        return view('central.website.listing', compact('tenant'));
    }
}
