<?php

namespace Webup\LaravelHelium\Core\Http\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class MenuComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $result = [];
        $adminUser = Auth::guard("admin")->user();
        foreach (config('helium.menu') as $label => $configMenu) {
            $menu = HeliumHelper::formatMenuForView($label, $configMenu);
            if ($this->hasPermissions($adminUser, $menu)) {
                $result[] = $menu;
            }
        }

        $view->with('menu', $result);
    }


    private function hasPermissions($user, $menu)
    {
        $hasPermissions = true;
        if (count($menu->permissions) > 0) {
            $hasPermissions = false;
            foreach ($menu->permissions as $key => $andPermissions) {
                if ($user->can(explode("|", $andPermissions))) {
                    $hasPermissions = true;
                    break;
                }
            }
        }

        if ($hasPermissions && isset($menu->isDropdown) && $menu->isDropdown) {
            foreach ($menu->urls as $urlKey => $subMenu) {
                if (!$this->hasPermissions($user, $subMenu)) {
                    unset($menu->urls[$urlKey]);
                }
            }
            if (count($menu->urls) == 0) {
                $hasPermissions = false;
            }
        }

        return $hasPermissions;
    }
}
