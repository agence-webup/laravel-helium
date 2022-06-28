<?php

namespace Webup\LaravelHelium\Core\Http\View\Components;

use Illuminate\View\Component;
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;

class BoxHeader extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, array $actions = [])
    {
        $this->title = $title;
        $this->actions = $actions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $sortedActions = [];
        $sortedWarning = [];
        $sortedDanger = [];
        foreach ($this->actions as $label => $action) {
            if (is_array($action) && array_key_exists("danger", $action) && $action["danger"] == true) {
                $sortedDanger[] = HeliumHelper::formatActionForView($label, $action);
            } else if (is_array($action) && array_key_exists("warning", $action) && $action["warning"] == true) {
                $sortedWarning[] = HeliumHelper::formatActionForView($label, $action);
            } else {
                $sortedActions[] = HeliumHelper::formatActionForView($label, $action);
            }
        }

        return view('helium::components.box-header', [
            "title" => $this->title,
            "actions" => array_merge($sortedActions, $sortedWarning, $sortedDanger)
        ]);
    }
}
