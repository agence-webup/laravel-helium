<?php

namespace Webup\LaravelHelium\Core\Helpers;

class HeliumHelper
{
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
            "urls" => $isDropdown ? array_map("self::formatLink", $configMenu["links"]) : [],
            "currentRoute" => $configMenu["current_route"]
        ];
    }

    public static function formatLink(string $link)
    {
        return substr($link, 0, 4) == "http" ? $link : route($link);
    }
}