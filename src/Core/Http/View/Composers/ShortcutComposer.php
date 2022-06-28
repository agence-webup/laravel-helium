<?php

namespace Webup\LaravelHelium\Core\Http\View\Composers;

use Illuminate\View\View;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class ShortcutComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $shortcuts = [];
        foreach (config('helium.shortcuts', []) as $containers) {
            $containeur = [];
            foreach ($containers as $label => $route) {
                $containeur[] = (object) [
                    "label" => $label,
                    "link" => HeliumHelper::formatLink($route)
                ];
            }
            $shortcuts[] = $containeur;
        }

        $view->with('shortcuts', $shortcuts);
    }
}
