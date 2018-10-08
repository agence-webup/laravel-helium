<?php

namespace Webup\LaravelHelium\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webup\LaravelHelium\Core\Http\Middleware\RedirectIfUnauthenticated;
use Webup\LaravelHelium\Core\Http\Middleware\RedirectIfAuthenticated;
use File;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang/', 'helium');

        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/helium'),
            __DIR__ . '/resources/views' => resource_path('views/vendor/helium'),
            __DIR__ . '/routes' => base_path('routes')
        ], 'helium');

        $router->aliasMiddleware('admin.auth', RedirectIfUnauthenticated::class);
        $router->aliasMiddleware('admin.guest', RedirectIfAuthenticated::class);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'helium');


        if (!$this->app->runningInConsole()) {
            if (!File::exists(base_path('routes') . '/admin.php')) {
                throw new \Exception("You need to publish laravel helium files (php artisan vendor:publish --tag=helium)", 42);
            }
            $this->loadRoutesFrom(base_path('routes') . '/admin.php');
        }


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
            'admins' => [
                'driver' => 'eloquent',
                'model' => \Webup\LaravelHelium\Core\Entities\AdminUser::class,
            ],
        ], $providersConfig));


    }
}