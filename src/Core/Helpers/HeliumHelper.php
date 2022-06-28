<?php

namespace Webup\LaravelHelium\Core\Helpers;

use Illuminate\Support\Arr;

class HeliumHelper
{

    public static function current_class($routeName, $cssClass = 'is-active')
    {
        if (is_array($routeName)) {
            $result = [];
            foreach ($routeName as $key => $value) {
                //Check if it's "routeName => class" or "routeName" format
                if (is_string($key)) {
                    $result[] = self::current_class($key, $value);
                } else {
                    $result[] = self::current_class($value, $cssClass);
                }
            }
            // Keep one unique value for each values
            $result = array_flip(array_flip($result));
            return implode(" ", $result);
        }

        if (!$routeName) {
            return '';
        }

        $currentRoute = app('router')->current()->getName();
        $rootPath = substr($currentRoute, 0, strlen($routeName));

        return ($rootPath == $routeName) ? $cssClass : '';
    }


    public static function formatActionForView(string $label, $action)
    {
        if (!is_array($action)) {
            $action = [
                'route' => $action
            ];
        }

        $route = array_key_exists("route", $action) ? $action["route"] : null;
        $danger = array_key_exists("danger", $action) && $action["danger"] == true;
        $warning = array_key_exists("warning", $action) && $action["warning"] == true;

        $cssClass = array_key_exists("class", $action) ? $action["class"] : null;
        unset($action['route']);
        unset($action['style']);
        unset($action['class']);
        unset($action['danger']);
        unset($action['warning']);
        return (object) [
            "label" => $label,
            "url" => $route ?  HeliumHelper::formatLink($route) : "",
            "style" => $danger ? "red" : ($warning ? "orange" : ""),
            "cssClass" => $cssClass,
            "others" => $action
        ];
    }

    public static function formatMenuForView(string $label, array $configMenu)
    {
        $isDropdown = array_key_exists('links', $configMenu);

        return (object) [
            "isDropdown" => $isDropdown,
            "label" => $label,
            "icon" => $configMenu["icon"],
            "url" => !$isDropdown ? HeliumHelper::formatLink($configMenu["url"]) : null,
            "urls" => $isDropdown ? collect($configMenu["links"])->map(function ($item) {
                return (object) [
                    "url" => HeliumHelper::formatLink(is_array($item) ? Arr::get($item, "url") : $item),
                    "permissions" => HeliumHelper::formatPermissions($item),
                ];
            })->toArray() : [],
            "currentRoute" => Arr::get($configMenu, "current_route"),
            "permissions" => HeliumHelper::formatPermissions($configMenu),
        ];
    }

    public static function formatPermissions($configMenu)
    {
        $menuPermissions = [];
        if (is_array($configMenu) && array_key_exists('permissions', $configMenu)) {
            $permissions = Arr::get($configMenu, "permissions", []);
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }
            $menuPermissions = array_merge($menuPermissions, $permissions);
        }

        return $menuPermissions;
    }

    public static function formatLink(string $link)
    {
        return substr($link, 0, 4) == "http" ? $link : route($link);
    }
}
