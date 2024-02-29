<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RoomKeeping extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($categories, $mixdate)
    {
        $this->data['categories'] = $categories;
        $this->data['mix_date'] = $mixdate;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.room-keeping', $this->data);
    }
}
