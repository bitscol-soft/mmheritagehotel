<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;

class InputFiled extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $name, $value = null, $placeholder = null, $isrequired = 0)
    {
        $this->data = [
            'title'         => $title ?? 'Enter '. ucfirst($name),
            'name'          => $name,
            'placeholder'   => $placeholder ?? $title,
            'is_required'   => $isrequired,
            'value'         => $value ?? old($name),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.widget.input-field', $this->data);
    }
}
