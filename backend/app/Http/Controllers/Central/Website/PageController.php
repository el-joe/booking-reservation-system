<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    private const ALLOWED_PAGES = ['about', 'contact', 'help', 'privacy', 'terms'];

    public function show(string $slug): View
    {
        abort_unless(in_array($slug, self::ALLOWED_PAGES), 404);

        return view("central.website.pages.{$slug}");
    }
}
