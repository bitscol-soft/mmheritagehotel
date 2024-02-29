<?php

namespace App\Exports;

use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Employee\EmployeeUpload;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class ExportEmployeeCSV implements FromView
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        
        $employees = $this->getExportEmployeeData($this->request);

        return view('employee.export_employee',compact('employees'));
    }


    public function getExportEmployeeData($request)
    {
        $relation = ['company.group', 'created_user', 'updated_user', 'grade', 'shift',
                'educational_qualification', 'experience', 'personal_information', 'guardian', 'reference_person',
                'bank_information.company_bank'];

        return Employee::with($relation)
                ->employment()
                ->orderByDesc('id')
                ->searchCompany()
                ->searchDepartment()
                ->searchDesignation()
                ->permissionEmployment()
                ->when($request->filled('grade_id'), function($q) use($request) {
                    $q->where('grade_id', $request->grade_id);
                })
                ->when($request->filled('line'), function($q) use($request) {
                    $q->where('line_id', $request->line);
                })
                ->when($request->filled('employee_type'), function($q) use($request) {
                    $q->where('employee_type', $request->employee_type);
                })
                ->when($request->filled('employee_id'), function($q) use($request) {
                    $q->where('employee_full_id', $request->employee_id);
                })
                ->when($request->filled('status'), function($q) use($request) {
                    $q->where('status', $request->status);
                })
                ->when($request->filled('card_no'), function($q) use($request) {
                    $q->where('card_no', $request->card_no);
                })
                ->when($request->filled('finger_print_id'), function($q) use($request) {
                    $q->where('finger_print_id', $request->finger_print_id);
                })
                ->when($request->filled('salary_type_id'), function($q) use($request) {
                    $q->whereHas('salaries', function ($q) use($request) {
                        $q->where('salary_type_id', $request->salary_type_id)->where('status', 1);
                    });
                })
                ->when($request->bank == 1 && !$request->cash, function($q) use($request) {
                    $q->whereHas('bank_information');
                })
                ->when($request->cash == 1 && !$request->bank, function($q) use($request) {
                    $q->whereDoesntHave('bank_information');
                })->get();
    }


}
