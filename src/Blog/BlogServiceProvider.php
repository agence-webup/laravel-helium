<?php

namespace Webup\LaravelHelium\Blog;

use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
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
        ], 'helium.blog');

        // views
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'helium');

        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/helium')
        ], 'helium.blog');
    }
}
