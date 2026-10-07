<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Accounting;

use App\DataTables\Tenant\JournalEntryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Services\Tenant\Accounting\JournalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function __construct(private JournalService $journalService) {}

    public function index(JournalEntryDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.accounting.journal.index');
    }

    public function create(): View
    {
        $accounts = Account::orderBy('code')->get();

        return view('tenant.accounting.journal.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => 'required|date',
            'reference' => 'required|string|unique:journal_entries,reference',
            'description' => 'required|string|max:255',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ]);

        $lines = collect($request->lines)->map(fn ($l): array => [
            'account_id' => $l['account_id'],
            'debit' => (float) ($l['debit'] ?? 0),
            'credit' => (float) ($l['credit'] ?? 0),
            'description' => $l['description'] ?? null,
        ])->toArray();

        $entry = $this->journalService->post($lines, $request->description, $request->reference);

        return redirect()->route('tenant.accounting.journal.show', $entry)
            ->with('success', 'Journal entry posted successfully.');
    }

    public function show(JournalEntry $entry): View
    {
        $entry->load(['lines.account']);

        return view('tenant.accounting.journal.show', compact('entry'));
    }

    public function void(JournalEntry $entry): RedirectResponse
    {
        if (str_starts_with($entry->reference, 'VOID-')) {
            return back()->with('error', 'This entry is already voided.');
        }

        $this->journalService->void($entry);

        return back()->with('success', 'Journal entry voided successfully.');
    }
}
