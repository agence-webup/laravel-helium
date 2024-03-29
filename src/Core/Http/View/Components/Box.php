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
    public function __construct(bool $padding = true, string $class = "")
    {
        $this->padding = $padding;
        $this->class = $class;
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
            "class" => $this->class,
        ]);
    }
}
