<?php

namespace Webup\LaravelHelium\Core\Http\View\Composers;

use Illuminate\View\View;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

use function PHPSTORM_META\map;

class MenuComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $menu = [];
        foreach (config('helium.menu') as $label => $configMenu) {
            $menu[] = HeliumHelper::formatMenuForView($label, $configMenu);
        }
        $view->with('menu', $menu);
    }
}
