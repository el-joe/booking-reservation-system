<?php

declare(strict_types=1);

namespace App\Services\Tenant\Resource;

use App\Models\AddOn;
use App\Models\PricingRule;
use App\Models\Resource;
use App\Models\ResourceAvailability;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResourceService
{
    public function create(array $data): Resource
    {
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        return Resource::create($data);
    }

    public function update(Resource $resource, array $data): Resource
    {
        if (isset($data['name']) && ! isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $resource->update($data);

        return $resource->fresh();
    }

    public function updateAvailability(Resource $resource, array $dates): void
    {
        foreach ($dates as $dateData) {
            ResourceAvailability::updateOrCreate(
                ['resource_id' => $resource->id, 'date' => $dateData['date']],
                [
                    'available_capacity' => $dateData['available_capacity'] ?? $resource->capacity,
                    'is_closed' => $dateData['is_closed'] ?? false,
                    'note' => $dateData['note'] ?? null,
                ]
            );
        }
    }

    /**
     * Calculate the total price for a booking.
     *
     * @param  array<int>  $addonIds
     * @return array{base: float, addons: float, total: float, nights_or_hours: int, breakdown: array<int, array<string, mixed>>}
     */
    public function calculatePrice(
        Resource $resource,
        Carbon $from,
        Carbon $to,
        int $guests,
        array $addonIds = []
    ): array {
        $nightsOrHours = (int) max(1, $from->diffInHours($to));

        // Determine the unit multiplier
        $unitMultiplier = match ($resource->price_unit) {
            'per_night' => (int) max(1, $from->diffInDays($to)),
            'per_hour' => $nightsOrHours,
            'per_person' => $guests,
            default => 1,
        };

        $nightsOrHours = $unitMultiplier;
        $basePrice = (float) $resource->base_price;
        $breakdown = [];

        // Apply pricing rules in priority order
        $applicableRules = $resource->pricingRules()
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->get();

        $pricePerUnit = $basePrice;

        foreach ($applicableRules as $rule) {
            if (! $this->ruleAppliesTo($rule, $from)) {
                continue;
            }

            $originalPrice = $pricePerUnit;

            $pricePerUnit = match ($rule->modifier_type) {
                'fixed' => $basePrice + (float) $rule->modifier_value,
                'percent' => $basePrice * (1 + ((float) $rule->modifier_value / 100)),
                default => $pricePerUnit,
            };

            $breakdown[] = [
                'rule' => $rule->name,
                'type' => $rule->rule_type,
                'modifier_type' => $rule->modifier_type,
                'modifier_value' => (float) $rule->modifier_value,
                'price_before' => $originalPrice,
                'price_after' => $pricePerUnit,
            ];

            break; // Apply only the highest priority matching rule
        }

        $baseTotal = round($pricePerUnit * $unitMultiplier, 2);

        // Add-ons
        $addonsTotal = 0.0;

        if (! empty($addonIds)) {
            $addons = AddOn::whereIn('id', $addonIds)
                ->where('is_active', true)
                ->get();

            foreach ($addons as $addon) {
                $addonPrice = match ($addon->price_type) {
                    'per_person' => (float) $addon->price * $guests,
                    default => (float) $addon->price,
                };

                $addonsTotal += $addonPrice;

                $breakdown[] = [
                    'addon' => $addon->name,
                    'price_type' => $addon->price_type,
                    'unit_price' => (float) $addon->price,
                    'total' => $addonPrice,
                ];
            }
        }

        return [
            'base' => $baseTotal,
            'addons' => round($addonsTotal, 2),
            'total' => round($baseTotal + $addonsTotal, 2),
            'nights_or_hours' => $nightsOrHours,
            'breakdown' => $breakdown,
        ];
    }

    private function ruleAppliesTo(PricingRule $rule, Carbon $date): bool
    {
        return match ($rule->rule_type) {
            'weekend' => $date->isWeekend(),
            'weekday' => $date->isWeekday(),
            'seasonal' => $rule->applies_from && $rule->applies_to
                && $date->between($rule->applies_from, $rule->applies_to),
            'dynamic' => true,
            default => false,
        };
    }
}
