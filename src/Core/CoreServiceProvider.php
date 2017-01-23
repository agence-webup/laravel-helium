<?php

namespace Webup\LaravelHelium\Core;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Database/migrations/' => database_path('migrations')
        ], 'helium');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            \Webup\LaravelHelium\Core\Console\AdminCreate::class,
            \Webup\LaravelHelium\Core\Console\AdminDelete::class,
            \Webup\LaravelHelium\Core\Console\AdminList::class,
            \Webup\LaravelHelium\Core\Console\AdminUpdate::class,
        ]);
    }
}
