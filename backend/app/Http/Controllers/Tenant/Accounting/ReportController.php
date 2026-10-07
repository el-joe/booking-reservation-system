<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Accounting;

use App\Http\Controllers\Controller;
use App\Services\Tenant\Accounting\JournalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private JournalService $journalService) {}

    public function trialBalance(Request $request): View
    {
        $asOf = $request->date ? Carbon::parse($request->date) : now();
        $rows = $this->journalService->getTrialBalance($asOf);

        return view('tenant.accounting.reports.trial-balance', compact('rows', 'asOf'));
    }

    public function balanceSheet(Request $request): View
    {
        $asOf = $request->date ? Carbon::parse($request->date) : now();
        $data = $this->journalService->getBalanceSheet($asOf);

        return view('tenant.accounting.reports.balance-sheet', compact('data', 'asOf'));
    }

    public function profitLoss(Request $request): View
    {
        $from = $request->date_from ? Carbon::parse($request->date_from) : now()->startOfMonth();
        $to = $request->date_to ? Carbon::parse($request->date_to) : now();
        $data = $this->journalService->getProfitLoss($from, $to);

        return view('tenant.accounting.reports.profit-loss', compact('data', 'from', 'to'));
    }

    public function cashFlow(): View
    {
        return view('tenant.accounting.reports.cash-flow');
    }
}
