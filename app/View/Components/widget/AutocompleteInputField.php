<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;

class AutocompleteInputFiled extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $name, $value = null, $placeholder = null, $isrequired = null)
    {
        $this->data = [
            'title'         => $title ?? 'Enter '. ucfirst($name),
            'name'          => $name,
            'placeholder'   => $placeholder ?? $title,
            'isrequired'    => $isrequired ?? 0,
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
        return view('components.widget.autocomplete-input-field', $this->data);
    }
}
