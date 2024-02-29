<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;

class GuestSelect extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    
    public function __construct($guests)
    {
        $this->data['guests'] = $guests;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.widget.guest-select', $this->data);
    }
}
