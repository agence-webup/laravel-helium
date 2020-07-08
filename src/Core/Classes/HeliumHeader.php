<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\HeliumHeader as HeliumHeaderContract;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class HeliumHeader implements HeliumHeaderContract
{

    protected $title = null;

    protected $saveAction = null;

    protected $addAction = null;

    protected $contextualActions = [];


    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function save(string $label, string $route)
    {
        $this->saveAction = (object) [
            "label" => $label,
            "url" => HeliumHelper::formatLink($route),
        ];

        return $this;
    }

    public function add(string $label, string $route)
    {
        $this->addAction = (object) [
            "label" => $label,
            "url" => HeliumHelper::formatLink($route),
        ];

        return $this;
    }

    public function contextual(array $actions)
    {
        $sortedActions = [];
        $sortedDanger = [];
        foreach ($actions as $label => $action) {
            if (is_array($action) && array_key_exists("dangerous", $action) && $action["dangerous"] == true) {
                $sortedDanger[] = HeliumHelper::formatActionForView($label, $action);
            } else {
                $sortedActions[] = HeliumHelper::formatActionForView($label, $action);
            }
        }

        $this->contextualActions = array_merge($sortedActions, $sortedDanger);
        return $this;
    }

    public function generate()
    {
        return view("helium::components.header", [
            "title" => $this->title,
            "saveAction" => $this->saveAction,
            "addAction" => $this->addAction,
            "contextualActions" => $this->contextualActions
        ]);
    }
}
