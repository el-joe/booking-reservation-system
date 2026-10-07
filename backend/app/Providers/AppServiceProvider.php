<?php

namespace App\Providers;

use App\Events\RefundProcessed;
use App\Listeners\PostRefundJournal;
use App\Services\Central\SeoService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(SeoService::class);

        Event::listen(RefundProcessed::class, PostRefundJournal::class);
    }
}
