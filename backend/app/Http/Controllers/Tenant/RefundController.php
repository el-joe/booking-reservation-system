<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\RefundDataTable;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Tenant\Finance\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
    ) {}

    public function index(RefundDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('tenant.finance.refunds.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($request->input('transaction_id'));

        $this->paymentService->processRefund(
            $transaction,
            (float) $request->input('amount'),
            $request->input('reason')
        );

        return redirect()->route('tenant.transactions.show', $transaction)
            ->with('success', 'Refund initiated successfully.');
    }
}
