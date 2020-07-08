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
    public function __construct(string $id, bool $padding = true, string $title = null)
    {
        $this->id = $id;
        $this->padding = $padding;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('helium::components.box', [
            "id" => $this->id,
            "padding" => $this->padding,
            "title" => $this->title,
        ]);
    }
}
