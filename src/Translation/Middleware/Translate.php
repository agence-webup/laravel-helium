<?php

namespace Webup\LaravelHelium\Translation\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;

class Translate
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new error binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $localeRepository = app(\Webup\LaravelHelium\Translation\LocaleRepository::class);

        $locale = $localeRepository->get($request->segment(1));

        if (!$locale) {
            abort(404);
        }

        app()->setLocale($locale->code);

        //Temp fix for multilang
        if ($locale->code == "fr") {
            setlocale(LC_TIME, 'fr_FR.utf8');
        } else {
            setlocale(LC_TIME, 'en_GB.utf8');
        }

        $this->view->share('locale', $locale);

        $this->view->share('supportedLocales', $localeRepository->supportedLocales());

        return $next($request);
    }
}
