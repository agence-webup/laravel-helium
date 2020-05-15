<?php

namespace Webup\LaravelHelium\Redirection;

use Illuminate\Support\ServiceProvider;
use Webup\LaravelHelium\Redirection\Http\Middleware\RedirectOldUrls;

class RedirectionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Contracts\Http\Kernel $kernel)
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'helium');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/routes/admin-redirection.php');
    }
}
