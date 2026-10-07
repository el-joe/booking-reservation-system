<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Procurement;

use App\DataTables\Tenant\PurchaseOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Services\Tenant\Procurement\PurchaseOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function __construct(private readonly PurchaseOrderService $service) {}

    public function index(PurchaseOrderDataTable $dataTable): mixed
    {
        return $dataTable->render('tenant.procurement.purchase-orders.index');
    }

    public function create(): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $purchaseRequests = PurchaseRequest::where('status', 'approved')->orderByDesc('created_at')->get();

        return view('tenant.procurement.purchase-orders.create', compact('suppliers', 'purchaseRequests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_request_id' => ['nullable', 'exists:purchase_requests,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'delivery_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        // Compute item totals
        $validated['items'] = array_map(function (array $item): array {
            $item['total'] = (float) $item['quantity'] * (float) $item['unit_price'];

            return $item;
        }, $validated['items']);

        $validated['tax'] = $validated['tax'] ?? 0;

        $this->service->create($validated);

        return redirect()->route('tenant.procurement.purchase-orders.index')
            ->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['supplier', 'purchaseRequest']);

        return view('tenant.procurement.purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('tenant.procurement.purchase-orders.edit', compact('purchaseOrder', 'suppliers'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'status' => ['required', 'in:draft,sent,received,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'delivery_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['tax'] = $validated['tax'] ?? 0;

        $purchaseOrder->update($validated);

        return redirect()->route('tenant.procurement.purchase-orders.index')
            ->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();

        return redirect()->route('tenant.procurement.purchase-orders.index')
            ->with('success', 'Purchase order deleted successfully.');
    }
}
