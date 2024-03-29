<?php

namespace Webup\LaravelHelium\Core\Classes;

use Webup\LaravelHelium\Core\Contracts\HeliumHeader as HeliumHeaderContract;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

/**
 * Helium Header helper
 *
 * Used to set header informations and actions
 */
class HeliumHeader implements HeliumHeaderContract
{

    protected $title = null;
    protected $saveAction = null;
    protected $addAction = null;
    protected $customAction = null;
    protected $customElems = [];
    protected $contextualActions = [];


    public function title(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function save(string $label, string $formId)
    {
        $this->saveAction = (object) [
            "label" => $label,
            "formId" => $formId,
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

    public function custom(string $label, array $attrs = [], string $icon = null)
    {
        $this->customAction = (object) [
            "attrs" => collect($attrs)->map(fn ($value, $key) => $key . '="' . $value . '"')->implode(" "),
            "icon" => $icon,
            "label" => $label,
        ];

        return $this;
    }

    public function pushAction(string $label, string $modifier = "primary", string $icon = null, array $attrs = [])
    {
        $this->customElems[] = (object) [
            "attrs" => collect($attrs)->map(fn ($value, $key) => $key . '="' . $value . '"')->implode(" "),
            "icon" => $icon,
            "label" => $label,
            "modifier" => $modifier,
            "isLink" => isset($attrs["href"]),
        ];

        return $this;
    }


    public function contextual(array $actions)
    {
        $sortedActions = [];
        $sortedWarning = [];
        $sortedDanger = [];
        foreach ($actions as $label => $action) {
            if (is_array($action) && array_key_exists("danger", $action) && $action["danger"] == true) {
                $sortedDanger[] = HeliumHelper::formatActionForView($label, $action);
            } else if (is_array($action) && array_key_exists("warning", $action) && $action["warning"] == true) {
                $sortedWarning[] = HeliumHelper::formatActionForView($label, $action);
            } else {
                $sortedActions[] = HeliumHelper::formatActionForView($label, $action);
            }
        }

        $this->contextualActions = array_merge($sortedActions, $sortedWarning, $sortedDanger);
        return $this;
    }

    public function generate()
    {
        return view("helium::components.header", [
            "title" => $this->title,
            "saveAction" => $this->saveAction,
            "addAction" => $this->addAction,
            "customAction" => $this->customAction,
            "customElems" => $this->customElems,
            "contextualActions" => $this->contextualActions,
        ]);
    }
}
