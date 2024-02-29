<?php

namespace App\View\Components\widget;

use Illuminate\View\Component;
use Module\Restaurant\Models\RstTableManage;

class TableSelect extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct($tables, $value = null)
    {
        $this->data['tables']   = $tables;
        $this->data['value']    = $value;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.widget.table-select', $this->data);
    }
}
