<?php

namespace Webup\LaravelHelium\Legals;

use Illuminate\Support\ServiceProvider;

class LegalsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'helium');

        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/helium')
        ], 'helium.legals');
    }
}
