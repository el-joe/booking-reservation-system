<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Enums\BookingType;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    public function show(string $slug): View
    {
        $bookingType = collect(BookingType::cases())->first(
            fn (BookingType $type) => $type->value === $slug
        );

        if ($bookingType === null) {
            throw new NotFoundHttpException;
        }

        $tenants = Tenant::query()
            ->where('status', 'active')
            ->where('business_type', $bookingType->value)
            ->paginate(12);

        return view('central.website.category', compact('bookingType', 'tenants'));
    }
}
