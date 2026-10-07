<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\DataTables\Tenant\InvoiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Services\Tenant\Finance\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    public function index(InvoiceDataTable $dataTable): mixed
    {
        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('tenant.finance.invoices.index');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['items', 'booking', 'customer']);

        return view('tenant.finance.invoices.show', compact('invoice'));
    }

    public function send(Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->sendByEmail($invoice);

        return redirect()->route('tenant.invoices.show', $invoice)
            ->with('success', 'Invoice sent to customer successfully.');
    }

    public function download(Invoice $invoice): Response
    {
        $path = $this->invoiceService->generatePdf($invoice);

        return response()->download($path, "{$invoice->invoice_number}.pdf");
    }

    public function markPaid(Invoice $invoice, Request $request): RedirectResponse
    {
        $transaction = null;
        if ($request->filled('transaction_id')) {
            $transaction = Transaction::find($request->input('transaction_id'));
        }

        $this->invoiceService->markAsPaid($invoice, $transaction);

        return redirect()->route('tenant.invoices.show', $invoice)
            ->with('success', 'Invoice marked as paid.');
    }
}
