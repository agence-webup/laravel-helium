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
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang/', 'helium');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'helium');

        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/helium'),
            __DIR__ . '/resources/views' => resource_path('views/vendor/helium')
        ], 'helium');


        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->defineAuth();
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

    /**
     * Update auth for package
     *
     * @return void
     */
    private function defineAuth()
    {
        //Get guards config
        $guardsConfig = $this->app['config']->get("auth.guards", []);
        //Merge default guard for blog
        $this->app['config']->set("auth.guards", array_merge([
            'admin' => [
                'driver' => 'session',
                'provider' => 'admins',
            ],
        ], $guardsConfig));

        //Get providers config
        $providersConfig = $this->app['config']->get("auth.providers", []);
        //Merge default provider for blog
        $this->app['config']->set("auth.providers", array_merge([
            'blogs' => [
                'driver' => 'eloquent',
                'model' => Webup\LaravelHelium\Core\Entities\AdminUser::class,
            ],
        ], $providersConfig));
    }
}