<?php

namespace Webup\LaravelHelium\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webup\LaravelHelium\Core\Http\Middleware\RedirectIfUnauthenticated;
use Webup\LaravelHelium\Core\Http\Middleware\RedirectIfAuthenticated;
use File;
use Illuminate\Support\Facades\Blade;
use Webup\LaravelHelium\Core\Classes\Helium;
use Webup\LaravelHelium\Core\Classes\HeliumBreadcrumb;
use Webup\LaravelHelium\Core\Classes\HeliumFlash;
use Webup\LaravelHelium\Core\Classes\HeliumHeader;
use Webup\LaravelHelium\Core\Contracts\Helium as HeliumContract;
use Webup\LaravelHelium\Core\Contracts\HeliumBreadcrumb as HeliumBreadcrumbContract;
use Webup\LaravelHelium\Core\Contracts\HeliumFlash as HeliumFlashContract;
use Webup\LaravelHelium\Core\Contracts\HeliumHeader as HeliumHeaderContract;
use Webup\LaravelHelium\Core\Http\Middleware\AuthorizeAdmin;
use Webup\LaravelHelium\Core\Http\Middleware\ShareAdminUser;



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
            __DIR__ . '/config/helium.php' => config_path('helium.php'),
            __DIR__ . '/Http/Controllers/Admin/PagesController.php' => app_path('Http/Controllers/Admin/PagesController.php'),
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/helium'),
            __DIR__ . '/resources/views' => resource_path('views/vendor/helium'),
            __DIR__ . '/routes/admin.php' => base_path('routes/admin.php')
        ], 'helium');

        $router->aliasMiddleware('admin.auth', RedirectIfUnauthenticated::class);
        $router->aliasMiddleware('admin.guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('admin.can', AuthorizeAdmin::class);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'helium');


        if (!$this->app->runningInConsole()) {
            if (!File::exists(base_path('routes') . '/admin.php')) {
                throw new \Exception("You need to publish laravel helium files (php artisan vendor:publish --tag=helium)", 42);
            }
            $this->loadRoutesFrom(base_path('routes') . '/admin.php');

            if (config('app.env') == 'local') {
                $this->loadRoutesFrom(__DIR__ . '/routes/crud.php');
            }
        }


        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }

        $this->loadBladeComponents();
    }

    private function loadBladeComponents()
    {
        Blade::component('helium-box', \Webup\LaravelHelium\Core\Http\View\Components\Box::class);
        Blade::component('helium-box-header', \Webup\LaravelHelium\Core\Http\View\Components\BoxHeader::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        require_once("helpers.php");

        $this->app->singleton('helium', function ($app) {
            return new Helium();
        });

        $this->app->singleton('helium.breadcrumb', function ($app) {
            return new HeliumBreadcrumb();
        });

        $this->app->singleton('helium.header', function ($app) {
            return new HeliumHeader();
        });

        $this->app->singleton('helium.flash', function ($app) {
            return new HeliumFlash();
        });

        $this->app->bind(HeliumFlashContract::class, 'helium.flash');
        $this->app->bind(HeliumBreadcrumbContract::class, 'helium.breadcrumb');
        $this->app->bind(HeliumHeaderContract::class, 'helium.header');
        $this->app->bind(HeliumContract::class, 'helium');


        $this->defineAuth();

        $this->mergeConfigFrom(
            __DIR__ . '/config/helium.php',
            'helium'
        );

        $this->app->register('Webup\LaravelHelium\Core\Providers\ViewServiceProvider');
        $this->app->register('Webup\LaravelHelium\Core\Providers\AuthServiceProvider');

        if (config()->get('helium.modules.redirection.enabled', false)) {
            $this->app->register('Webup\LaravelHelium\Redirection\RedirectionServiceProvider');
            $menuConfig = $this->app['config']->get("helium.menu.Outils", [
                "current_route" => "tools",
                "icon" => "sliders",
                "links" => [
                    "Redirections" => "tools.redirection.index",
                ]
            ]);
            $this->app['config']->set("helium.menu.Outils", $menuConfig);
        }

        // if (config()->get('helium.modules.setting.enabled', false)) {
        //     $this->app->register('Webup\LaravelHelium\Setting\SettingServiceProvider');
        //     $menuConfig = $this->app['config']->get("helium.menu.Outils", [
        //         "current_route" => "admin.tools",
        //         "icon" => "sliders",
        //         "links" => [
        //             "Paramètres" => "admin.tools.redirection.index",
        //         ]
        //     ]);
        //     $this->app['config']->set("helium.menu.Outils", $menuConfig);
        // }
    }
    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            HeliumContract::class,
            HeliumBreadcrumbContract::class,
            HeliumHeaderContract::class,
            HeliumFlashContract::class,
            'helium',
            'helium.breadcrumb',
            'helium.header',
            'helium.flash',
        ];
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
            \Webup\LaravelHelium\Core\Console\CrudCreate::class,
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
