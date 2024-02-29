<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;



class ExportService
{
    public function exportData($data, $file_path, $filename)
    {

        set_time_limit(0);

        if (request('export_type') == 'pdf') {

            ini_set("pcre.backtrack_limit", "50000000");


            $pdf = \PDF::loadview($file_path . request('export_type'), $data, [], [
                'margin_header'         => 10,
                'margin_footer'         => 5,
                'mode'                  => 'utf-8',
                'format'                => 'A4',
            ]);

            $pdf->getMpdf()->setFooter("{PAGENO} / {nb}");

            return $pdf->stream($filename . '.pdf');
        }


        if (request('export_type') == 'excel') {

            $data['file_path'] = $file_path;
            return Excel::download(new ExportExcelService($data), $filename . '.xlsx');
        }
    }
}
