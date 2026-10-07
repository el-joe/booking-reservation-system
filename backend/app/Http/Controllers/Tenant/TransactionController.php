<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\TransactionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(TransactionDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('tenant.finance.transactions.index');
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['booking', 'customer', 'refund']);

        return view('tenant.finance.transactions.show', compact('transaction'));
    }
}
