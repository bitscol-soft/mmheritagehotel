<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Module\Hotel\Models\HotelTransactionLedger;

class MultiAccountPayModalBooking extends Component
{
    public $data;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($payable = 0, $sourceid = null, $sourcetype = null)
    {
        $this->data = [
            'payable'       => $payable,
            'collections'   => $sourceid != null && $sourcetype != null ? HotelTransactionLedger::where('source_id', $sourceid)->where('source_type', $sourcetype)->get() : collect([]),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.multi-account-pay-modal-booking', $this->data);
    }
}
