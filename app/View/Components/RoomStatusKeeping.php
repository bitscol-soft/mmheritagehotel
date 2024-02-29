<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RoomStatusKeeping extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($status, $room, $statusvalue, $category)
    {
        $this->data['status']       = $status;
        $this->data['status_val']   = $statusvalue;
        $this->data['room']         = $room;
        $this->data['category']     = $category;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.room-status-keeping', $this->data);
    }
}
