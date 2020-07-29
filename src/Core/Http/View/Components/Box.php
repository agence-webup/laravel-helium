<?php

namespace Webup\LaravelHelium\Core\Http\View\Components;

use Illuminate\View\Component;

class Box extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(bool $padding = true)
    {
        $this->padding = $padding;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('helium::components.box', [
            "padding" => $this->padding,
        ]);
    }
}
