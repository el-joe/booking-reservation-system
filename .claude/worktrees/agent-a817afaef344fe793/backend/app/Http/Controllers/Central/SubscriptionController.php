<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        return view('central.subscriptions.index');
    }

    public function update(Request $request, string $subscription): RedirectResponse
    {
        return redirect()->route('central.subscriptions.index');
    }
}
