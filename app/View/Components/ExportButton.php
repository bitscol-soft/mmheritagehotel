<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ExportButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pdf = 0, $excel = 0, $print = 0)
    {
        $this->data = [
            'pdf'   => $pdf,
            'excel' => $excel,
            'print' => $print,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.export-button', $this->data);
    }
}
