<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $accounts = Account::with('children')
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get()
            ->groupBy('type');

        return view('tenant.accounting.accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        $parents = Account::orderBy('code')->get();

        return view('tenant.accounting.accounts.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
        ]);

        Account::create($data);

        return redirect()->route('tenant.accounting.accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function show(Account $account): View
    {
        $account->load('children', 'parent');

        return view('tenant.accounting.accounts.show', compact('account'));
    }

    public function edit(Account $account): View
    {
        $parents = Account::where('id', '!=', $account->id)->orderBy('code')->get();

        return view('tenant.accounting.accounts.edit', compact('account', 'parents'));
    }

    public function update(Account $account, Request $request): RedirectResponse
    {
        $rules = [
            'code' => 'required|string|unique:accounts,code,'.$account->id,
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
        ];

        if (! $account->is_system) {
            $rules['type'] = 'required|in:asset,liability,equity,revenue,expense';
        }

        $data = $request->validate($rules);

        if ($account->is_system) {
            unset($data['type']);
        }

        $account->update($data);

        return redirect()->route('tenant.accounting.accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        if ($account->journalLines()->exists()) {
            return back()->with('error', 'Cannot delete account with journal entries.');
        }

        if ($account->is_system) {
            return back()->with('error', 'Cannot delete a system account.');
        }

        $account->delete();

        return redirect()->route('tenant.accounting.accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}
