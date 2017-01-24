<?php

namespace Webup\LaravelHelium\Contact;

use Illuminate\Support\ServiceProvider;
use Webup\LaravelHelium\Contact\Services\ContactService;

class ContactServiceProvider extends ServiceProvider
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
        ], 'helium.contact');

        // views
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'helium');

        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/helium')
        ], 'helium.contact');

        // config
        $this->publishes([
            __DIR__.'/Config/contact.php' => config_path('contact.php'),
        ], 'helium.contact');

        $this->registerContactService();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/contact.php', 'contact'
        );
    }

    /**
     * Register a resolver for the authenticated user.
     *
     * @return void
     */
    protected function registerContactService()
    {
        $this->app->singleton('Webup\LaravelHelium\Contact\Contracts\ContactService', function () {
            $settingsManager = $this->app->make('anlutro\LaravelSettings\SettingsManager');
            return new ContactService($settingsManager, $this->app['config']['contact']['model']);
        });
    }
}
