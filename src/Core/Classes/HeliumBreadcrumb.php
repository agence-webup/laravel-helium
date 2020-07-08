<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\HeliumBreadcrumb as HeliumBreadcrumbContract;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class HeliumBreadcrumb implements HeliumBreadcrumbContract
{
    protected $items = [];

    public function push(string $label, string $route)
    {
        $this->items[] = (object) [
            "label" => $label,
            "link" => HeliumHelper::formatLink($route)
        ];

        return $this;
    }


    public function generate()
    {
        if (count($this->items) < 1) {
            return null;
        }
        return view("helium::elements.breadcrumb", [
            "items" => $this->items,
        ]);
    }
}
