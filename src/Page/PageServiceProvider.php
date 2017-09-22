<?php

namespace Webup\LaravelHelium\Page;

use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // migrations
        $this->publishes([
            __DIR__.'/Database/migrations/' => database_path('migrations')
        ], 'helium.page');

        // views
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'helium');

        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/helium')
        ], 'helium.page');
    }
}
