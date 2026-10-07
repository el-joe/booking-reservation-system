<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Procurement;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Services\Tenant\Procurement\PurchaseOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseRequestController extends Controller
{
    public function __construct(private readonly PurchaseOrderService $service) {}

    public function index(): View
    {
        $pending = PurchaseRequest::with('requestedBy')->where('status', 'pending')->latest()->get();
        $approved = PurchaseRequest::with('requestedBy')->where('status', 'approved')->latest()->get();
        $rejected = PurchaseRequest::with('requestedBy')->where('status', 'rejected')->latest()->get();

        return view('tenant.procurement.purchase-requests.index', compact('pending', 'approved', 'rejected'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.estimated_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $totalEstimated = collect($validated['items'])
            ->sum(fn ($item) => (float) $item['quantity'] * (float) $item['estimated_price']);

        PurchaseRequest::create([
            'requested_by_id' => auth()->id(),
            'title' => $validated['title'],
            'items' => $validated['items'],
            'total_estimated' => $totalEstimated,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('tenant.procurement.purchase-requests.index')
            ->with('success', 'Purchase request submitted successfully.');
    }

    public function approve(PurchaseRequest $request): RedirectResponse
    {
        $this->service->approvePurchaseRequest($request);

        return back()->with('success', 'Purchase request approved.');
    }

    public function reject(Request $httpRequest, PurchaseRequest $request): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->service->rejectPurchaseRequest($request, $validated['reason']);

        return back()->with('success', 'Purchase request rejected.');
    }
}
