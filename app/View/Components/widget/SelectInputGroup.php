<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;

class SelectInputGroup extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $isrequired = 0, $name, $placeholder = null, $collections =[], $selected = null, $secondvalue = null)
    {
        $this->data = [
            'title'         => $title ?? ucfirst($name),
            'isrequired'    => $isrequired ?? 0,
            'name'          => $name,
            'placeholder'   => $placeholder ?? 'Enter '. $title,
            'collections'   => $collections,
            'selected'      => $selected,
            'secondvalue'   => $secondvalue,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.select-input-group', $this->data);
    }
}
