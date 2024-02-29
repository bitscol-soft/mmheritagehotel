<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;

class TextInputGroup extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $name, $placeholder = null, $value = null, $readonly = 0, $isrequired = null, $class = null)
    {
        $this->data = [
            'title'         => $title ?? ucfirst($name),
            'isrequired'    => $isrequired ?? 0,
            'name'          => $name,
            'placeholder'   => $placeholder ?? 'Enter '. $title,
            'value'         => $value,
            'readonly'      => $readonly,
            'class'         => $class,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.text-input-group', $this->data);
    }
}
