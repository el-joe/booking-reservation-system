<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function index(): View
    {
        return view('central.tenants.index');
    }

    public function create(): View
    {
        return view('central.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }

    public function show(string $tenant): View
    {
        return view('central.tenants.show', compact('tenant'));
    }

    public function edit(string $tenant): View
    {
        return view('central.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, string $tenant): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }

    public function destroy(string $tenant): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }

    public function suspend(string $tenant): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }

    public function reactivate(string $tenant): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }

    public function impersonate(string $tenant): RedirectResponse
    {
        return redirect()->route('central.tenants.index');
    }
}
