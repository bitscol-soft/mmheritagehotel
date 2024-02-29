<?php

namespace App\Exports;

use Module\Garments\Models\Merchandising\Order\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OtAndHolidayExport implements FromView, ShouldAutoSize
{

    public $exportData = [];

    public function __construct($data)
    {
        $this->exportData = $data;
    }

    public function view(): View
    {
        $data = $this->exportData;

        return view('ot-holiday.export', $data);
    }

}
