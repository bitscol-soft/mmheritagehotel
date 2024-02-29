<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TableColumns extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($table = null, $collections = null)
    {
        $this->data = [
            'table'         => $table,
            'collections'   => $collections,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table-columns', $this->data);
    }
}
