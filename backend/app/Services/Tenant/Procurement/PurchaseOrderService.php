<?php

declare(strict_types=1);

namespace App\Services\Tenant\Procurement;

use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;

class PurchaseOrderService
{
    public function generatePoNumber(): string
    {
        $count = PurchaseOrder::withTrashed()->count();

        return 'PO-'.date('Y').'-'.str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT);
    }

    public function create(array $data): PurchaseOrder
    {
        $data['po_number'] = $this->generatePoNumber();

        return PurchaseOrder::create($data);
    }

    public function approvePurchaseRequest(PurchaseRequest $request): PurchaseRequest
    {
        $request->update([
            'status' => 'approved',
            'approved_by_id' => auth()->id(),
        ]);

        return $request->fresh();
    }

    public function rejectPurchaseRequest(PurchaseRequest $request, string $reason): PurchaseRequest
    {
        $request->update([
            'status' => 'rejected',
            'notes' => $reason,
        ]);

        return $request->fresh();
    }
}
