<?php

declare(strict_types=1);

namespace App\Services\Tenant\Accounting;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JournalService
{
    public function post(array $lines, string $description, string $reference, ?Model $source = null): JournalEntry
    {
        $totalDebits = collect($lines)->sum('debit');
        $totalCredits = collect($lines)->sum('credit');

        if (round((float) $totalDebits, 2) !== round((float) $totalCredits, 2)) {
            throw ValidationException::withMessages([
                'lines' => 'Journal entry is not balanced. Debits must equal credits.',
            ]);
        }

        return DB::transaction(function () use ($lines, $description, $reference, $source): JournalEntry {
            $entry = JournalEntry::create([
                'reference' => $reference,
                'date' => now()->toDateString(),
                'description' => $description,
                'created_by_id' => auth()->id(),
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source?->getKey(),
            ]);

            foreach ($lines as $line) {
                JournalLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null,
                ]);
            }

            return $entry->load('lines');
        });
    }

    public function void(JournalEntry $entry): void
    {
        $reversingLines = $entry->lines->map(fn (JournalLine $line): array => [
            'account_id' => $line->account_id,
            'debit' => $line->credit,
            'credit' => $line->debit,
            'description' => 'Reversal: '.$line->description,
        ])->toArray();

        $this->post(
            $reversingLines,
            'VOID: '.$entry->description,
            'VOID-'.$entry->reference,
        );
    }

    public function getTrialBalance(Carbon $asOf): array
    {
        $accounts = Account::with(['journalLines' => function ($q) use ($asOf): void {
            $q->whereHas('journalEntry', fn ($q) => $q->where('date', '<=', $asOf->toDateString()));
        }])->get();

        $rows = [];
        foreach ($accounts as $account) {
            $debits = $account->journalLines->sum('debit');
            $credits = $account->journalLines->sum('credit');
            if ($debits > 0 || $credits > 0) {
                $rows[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'type' => $account->type,
                    'debit' => (float) $debits,
                    'credit' => (float) $credits,
                ];
            }
        }

        return $rows;
    }

    public function getBalanceSheet(Carbon $asOf): array
    {
        $accounts = Account::with(['journalLines' => function ($q) use ($asOf): void {
            $q->whereHas('journalEntry', fn ($q) => $q->where('date', '<=', $asOf->toDateString()));
        }])->get();

        $result = ['assets' => [], 'liabilities' => [], 'equity' => []];

        foreach ($accounts as $account) {
            if (! in_array($account->type, ['asset', 'liability', 'equity'], true)) {
                continue;
            }

            $debits = (float) $account->journalLines->sum('debit');
            $credits = (float) $account->journalLines->sum('credit');
            $balance = in_array($account->type, ['asset'], true)
                ? $debits - $credits
                : $credits - $debits;

            $result["{$account->type}s"][] = [
                'code' => $account->code,
                'name' => $account->name,
                'balance' => $balance,
            ];
        }

        return $result;
    }

    public function getProfitLoss(Carbon $from, Carbon $to): array
    {
        $accounts = Account::with(['journalLines' => function ($q) use ($from, $to): void {
            $q->whereHas('journalEntry', fn ($q) => $q->whereBetween('date', [$from->toDateString(), $to->toDateString()]));
        }])->whereIn('type', ['revenue', 'expense'])->get();

        $revenue = [];
        $expenses = [];

        foreach ($accounts as $account) {
            $debits = (float) $account->journalLines->sum('debit');
            $credits = (float) $account->journalLines->sum('credit');

            if ($account->type === 'revenue') {
                $revenue[] = ['code' => $account->code, 'name' => $account->name, 'balance' => $credits - $debits];
            } else {
                $expenses[] = ['code' => $account->code, 'name' => $account->name, 'balance' => $debits - $credits];
            }
        }

        $totalRevenue = collect($revenue)->sum('balance');
        $totalExpenses = collect($expenses)->sum('balance');

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net' => $totalRevenue - $totalExpenses,
        ];
    }
}
