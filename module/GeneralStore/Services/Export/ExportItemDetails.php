<?php

namespace Module\GeneralStore\Services\Export;

use App\Exports\GeneralStore;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

// class ExportDataAsCSV implements FromView, ShouldAutoSize, WithEvents
class ExportItemDetails implements FromView, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data        = [];
    function __construct($data)
    {
        $this->data = $data;
    }

    // exportable view
    public function view(): View
    {
        return view('export.gs.item_details', $this->data);
    }


    /**
     * customize excel sheet
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getColumnDimension('A')->setWidth(12);
                $event->sheet->getColumnDimension('E')->setWidth(18);
                $event->sheet->getColumnDimension('I')->setWidth(18);
            }
        ];
    }
}
