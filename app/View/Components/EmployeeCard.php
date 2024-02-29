<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EmployeeCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($employee = null)
    {
        $this->data = [
            'employee' => $employee
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employee-card', $this->data);
    }
}
