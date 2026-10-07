<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    private const ALLOWED_PAGES = ['about', 'contact', 'help', 'terms', 'privacy'];

    public function show(string $slug): View
    {
        if (! in_array($slug, self::ALLOWED_PAGES, true)) {
            throw new NotFoundHttpException;
        }

        return view("central.website.pages.{$slug}");
    }
}
