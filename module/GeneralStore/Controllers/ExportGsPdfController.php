<?php

namespace Module\GeneralStore\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\GeneralStore\Services\Export\GSExportData;
use PDF;

class ExportGsPdfController extends Controller
{
    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");

        
        $hrm_data             = new GSExportData($request);

        $data['headers']      = $hrm_data->getHeadersName();
        $data['company_name'] = $hrm_data->getCompanyName();
        $data['heading']      = $hrm_data->getHeadingName();
        $data['datas']        = $hrm_data->getExportableData();

        $mpdf = PDF::loadView('gs-exports.pdf-common', $data);

        

        return ($mpdf)->stream(Carbon::parse(now())->format('Y/m/d/') . $data['heading'] . '.pdf');
    }
}
