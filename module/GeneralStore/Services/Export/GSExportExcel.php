<?php

namespace Module\GeneralStore\Services\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

// class ExportDataAsCSV implements FromView, ShouldAutoSize, WithEvents
class GSExportExcel implements FromView, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $request        = [];
    function __construct($request)
    {
        $this->request = $request;
    }

    // exportable view
    public function view(): View
    {
        $export_data          = new GSExportExcelData($this->request);

        
        $data['headers']      = $export_data->getHeadersName();
        $data['company_name'] = $export_data->getCompanyName();
        $data['heading']      = $this->request->model;
        $data['datas']        = $export_data->getExportableData();

        return view('gs-exports.excel-common', $data);
    }


    /**
     * customize excel sheet
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // Company Heding
                $cellRange2 = 'A2:W2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(30);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(20);

                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ'];

                foreach ($columns as $key => $column) {
                    if ($key == 0)
                        $event->sheet->getColumnDimension($column)->setWidth(6);
                    else
                        $event->sheet->getColumnDimension($column)->setWidth(20);
                }
            }
        ];
    }
}
