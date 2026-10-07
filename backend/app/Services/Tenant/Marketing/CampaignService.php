<?php

declare(strict_types=1);

namespace App\Services\Tenant\Marketing;

use App\Jobs\Tenant\SendCampaignJob;
use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CampaignService
{
    public function create(array $data): Campaign
    {
        $data['created_by_id'] = Auth::id();
        $data['status'] = 'draft';

        return Campaign::create($data);
    }

    public function send(Campaign $campaign): void
    {
        $recipientCount = $this->getAudienceCount($campaign->audience_filter ?? []);

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'recipients_count' => $recipientCount,
        ]);

        SendCampaignJob::dispatch($campaign);
    }

    public function getAudienceCount(array $filter): int
    {
        return $this->buildAudienceQuery($filter)->count();
    }

    /**
     * @return array{recipients: int, opens: int, clicks: int, conversions: int, open_rate: float, click_rate: float, conversion_rate: float}
     */
    public function getPerformanceStats(Campaign $campaign): array
    {
        return [
            'recipients' => $campaign->recipients_count,
            'opens' => $campaign->opens_count,
            'clicks' => $campaign->clicks_count,
            'conversions' => $campaign->conversions_count,
            'open_rate' => $campaign->open_rate,
            'click_rate' => $campaign->click_rate,
            'conversion_rate' => $campaign->conversion_rate,
        ];
    }

    private function buildAudienceQuery(array $filter): \Illuminate\Database\Eloquent\Builder
    {
        $query = Customer::query();

        if (! empty($filter['tags'])) {
            $query->whereJsonContains('tags', $filter['tags']);
        }

        if (! empty($filter['last_booking_from'])) {
            $query->whereHas('bookings', function ($q) use ($filter): void {
                $q->where('created_at', '>=', $filter['last_booking_from']);
            });
        }

        if (! empty($filter['last_booking_to'])) {
            $query->whereHas('bookings', function ($q) use ($filter): void {
                $q->where('created_at', '<=', $filter['last_booking_to']);
            });
        }

        if (! empty($filter['min_spent'])) {
            $query->whereHas('bookings', function ($q) use ($filter): void {
                $q->selectRaw('customer_id, SUM(total_amount) as total')
                    ->groupBy('customer_id')
                    ->havingRaw('SUM(total_amount) >= ?', [(float) $filter['min_spent']]);
            });
        }

        if (! empty($filter['max_spent'])) {
            $query->whereHas('bookings', function ($q) use ($filter): void {
                $q->selectRaw('customer_id, SUM(total_amount) as total')
                    ->groupBy('customer_id')
                    ->havingRaw('SUM(total_amount) <= ?', [(float) $filter['max_spent']]);
            });
        }

        return $query;
    }
}
