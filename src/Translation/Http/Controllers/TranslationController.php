<?php

namespace Webup\LaravelHelium\Translation\Http\Controllers;

use App\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public function change($locale)
    {
        $localeRepository = app(\Webup\LaravelHelium\Translation\LocaleRepository::class);
        $locale = $localeRepository->get($locale);

        if (!$locale) {
            abort(404);
        }

        app()->setLocale($locale->code);
        
        return redirect(trans_route('home'));
    }
}
