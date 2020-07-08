<?php

namespace Webup\LaravelHelium\Core\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer(
            'helium::elements.menu',
            'Webup\LaravelHelium\Core\Http\View\Composers\MenuComposer'
        );
        View::composer(
            'helium::elements.shorcuts',
            'Webup\LaravelHelium\Core\Http\View\Composers\ShortcutComposer'
        );
    }
}
