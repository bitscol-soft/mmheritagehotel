<?php

namespace App\Exports;

use Module\HRM\Models\Attendance\Attendance;
use App\Models\Company;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Module\HRM\Models\Employee\Employee;
use Module\HRM\Models\Grade;
use Module\HRM\Models\Line;
use Module\HRM\Models\Salary\Disbursement;
use Module\HRM\Models\Salary\HrLoan;
use Module\HRM\Models\Salary\Salary;
use Carbon\Carbon;

use Module\HRM\Models\Attendance\OutsideWork;
use Module\HRM\Models\Leave\LeaveApplication;
use Module\HRM\Models\Leave\LeaveType;
use Illuminate\Support\Facades\DB;
use Module\HRM\Models\Attendance\AttendanceBonusDetails;
use Module\HRM\Models\FixedBonus;
use Module\HRM\Models\FixedBonusDetail;
use Module\HRM\Models\Leave\ApprovalAuthor\ApplicantDesignation;
use Module\HRM\Models\Leave\ShortLeaveAuthor\SLeaveAppDesig;
use Module\HRM\Models\Leave\ShortLeaveApplication;
use Module\HRM\Models\Leave\ShortLeaveSetup;
use Module\HRM\Models\ProductionSalary\BasicRateSetup;

use function foo\func;

// class ExportDataAsCSV implements FromView, ShouldAutoSize, WithEvents
class HrmExportData
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public $request = [];
    function __construct($request)
    {

        $this->request = $request;
    }

    // Set company name for list
    public function getCompanyName()
    {
        if ($this->request->filled('company')) {
            return Company::where('id', $this->request->company)->first()->name;
        } else {
            return "All Companies";
        }
    }
    // Set heading name for list
    public function getHeadingName()
    {
        if ($this->request->model == "Bonus Detail List") {
            $bonus = FixedBonus::where('id', $this->request->bonus_id)->with('company', 'department')->first();
            return "Bonus of " . $bonus->bonus_type->type . " For the month " . $bonus->effected_from;
        } else if ($this->request->model == "Bonus Disbursement List") {
            return "Disbursement List";
        } else {
            return $this->request->model;
        }
    }



    public function getExportableData()
    {
        $request = $this->request;
        if ($request->model == "Disbursement List") {
            return $this->disbursementList($request);
        } else if ($request->model == "Disbursement Create") {
            return $this->disbursementCreate($request);
        } else if ($request->model == "Increment List") {
            return $this->incrementList($request);
        } else if ($request->model == "Employee List" || $request->model == "Deactivate Employee List") {
            return $this->employeeList($request);
        } else if ($request->model == "Hr Laon List") {
            return $this->hrLoanList($request);
        } else if ($request->model == "Salary List") {
            return $this->salaryList($request);
        } else if ($request->model == "Department List") {
            return $this->departmentList($request);
        } else if ($request->model == "Designation List") {
            return $this->designationList($request);
        } else if ($request->model == "Grade List") {
            return $this->gradeList($request);
        } else if ($request->model == "Line List") {
            return $this->lineList($request);
        } else if ($request->model == "Bonus List") {
            return $this->bonusList($request);
        } else if ($request->model == "Bonus Detail List") {
            return $this->bonusDetailList($request);
        } else if ($request->model == "Bonus Disbursement List") {
            return $this->bonusDisbursementList($request);
        } else if ($request->model == "Attendance Bonus List") {
            return $this->attendanceBonusList($request);
        } else if ($request->model == "HR Out Worker List") {
            return $this->outWorkList($request);
        } else if ($request->model == "Manual All Attendance List") {
            return $this->manualAllAttendanceList($request);
        } else if ($request->model == "Leave Type") {
            return $this->leaveTypeList($request);
        } else if ($request->model == "Employee Leave Application (All)") {
            return $this->leaveApplicationList($request);
        } else if ($request->model == "Cancel Leave Application") {
            return $this->applicationCancelList($request);
        } else if ($request->model == "Pending Approval Leave Application") {
            return $this->pendingApprovalApplicationList($request);
        } else if ($request->model == "Pending Recommend Leave Application") {
            return $this->pendingRecommendApplicationList($request);
        } else if ($request->model == "Short Leave Application List") {
            return $this->shortLeaveApplicationList($request);
        } else if ($request->model == "Late Adjustment") {
            return $this->lateAdjustment();
        } else if ($request->model == "Salary Increment Form") {
            return $this->salaryIncrementForm();
        } else if ($request->model == "Bonus Create Form") {
            return $this->bonusCreateForm();
        }
    }


    // #########################    get header name
    public function getHeadersName()
    {
        $request = $this->request;
        if ($request->model == "Disbursement List") {
            return ["Applicable", "Employee Id", "Employee Name", "Department", "Designation", "Join Date", "Type", "Value", "OT Time"];

        } else  if ($request->model == "Disbursement Create") {
            return ["Employee Id", "Employee Name", "Department", "Designation", "Joining Date"];

        } else  if ($request->model == "Increment List") {
            return ["Employee Id", "Employee Name", "Department", "Designation", "Join Date", "Year", "Increment", "Increment%", "Increment Date", "Gross", "Bank", "Cash"];

        } else if ($request->model == "Employee List" || $request->model == "Deactivate Employee List") {
            if ($request->routeIs('export.as.pdf')) {
                return ['Employee Full Id', 'Name', 'Joining Date', 'Company', 'Department', 'Designation', 'Card No', 'Status'];
            }

            return ["SL", "Company", "Employee Name", "Employee Id", "Old Id", "Joining Date", "Department", "Designation", "Grade", "Line", "Father/Husband's Name", "Mothers's Name", "Date of Birth", "Gender", "Marital Status", "Religion", "Present Address", "Present Phone", "Permanent Address", "Permanent Phone", "Email", "Nationality", "National Id", "Employee Type", "P.Bonus Type", "Device Id", "Finger Print Id", "Gross Salary", "Mfs Type", "Mfs", "Card No", "Height", "Weight", "Phone Number", "Blood Group", "Guardian Name", "Guardian Phone 1", "Guardian Phone 2", "Guardian Relation", "Guardian Address", "Reference Name", "Reference Phone 1", "Reference Phone 2", "Reference Relation", "Reference Address", "Bank", "Account No", "E.Tin No", "Examination", "Number", "Passing Year", "Board",  "Designation", "Duration", "Spouse Name", "Employment Status"];

        } else if ($request->model == "Hr Laon List") {
            return ["Employee Id", "Employee Name", "Department", "Designation", "Sanction", "Amount", "Terms", "Status", "Adjustment", "Balance", "Approval"];

        } else if ($request->model == "Salary List") {
            return ["Employee Id", "Employee Name", "Grade", "Department", "Designation", "Joining Date", "Permanent Date", "W.E From", "Increment", "Full Salary"];

        } else if ($request->model == "Department List") {
            return ["Department Name", "Department Code", "Created At", "Updated At"];

        } else if ($request->model == "Designation List") {
            return ["Designation Name", "Designation Code", "Created At", "Updated At"];

        } else if ($request->model == "Grade List") {
            return ["Grade Name", "Grade Code", "Created At", "Updated At"];

        } else if ($request->model == "Line List") {
            return ["Name", "Created At", "Updated At"];

        }  else if ($request->model == "Bonus List") {
            return ["Date", "Manpower", "Bonus Type", "Company", "Department", "Designation", "Payment Type", "Amount", "Bank", "Cash", "Generated By", "Approved By"];

        } else if ($request->model == "Bonus Disbursement List") {
            return ["Date", "Manpower", "Bonus Type", "Company", "Department", "Payment Type", "Amount", "Bank Amount", "Cash Amount"];

        }  else if ($request->model == "Bonus Detail List") {

            $fixedBonus = FixedBonus::find($this->request->bonus_id);
            
            $basic_rate_setup = BasicRateSetup::orderByDesc('effected_month')
            ->where('company_id', $fixedBonus->company_id)
            ->where('department_id', $fixedBonus->department_id)
            ->where('effected_month', '<=', $fixedBonus->effected_from)->first();

            if(strtolower($fixedBonus->bonus_details->first()->employee->employee_type) == "production") {
                return ["Employee ID", "Employee Name", "Department", "Designation", "Joining Date", "Basic Salary", "Amount", "Signature", "Stamp"];
            } else {
                return ["Employee ID", "Employee Name", "Department", "Designation", "Joining Date", "Current Salary", "Amount", "Bank", "Cash", "Signature", "Stamp"];
            }

        } else if ($request->model == "Attendance Bonus List") {
            return ["Employee ID", "Employee Name", "Company", "Department", "Designation", "Type", "Month", "Bonus"];
        } else if ($request->model == "HR Out Worker List") {
            return ["Employee ID", "Employee Name", "Company", "Created Time", "Working Date", "Expected Return Time", "Actual Return Time",  "Work Note"];
        } else if ($request->model == "Manual All Attendance List") {
            return ["Employee ID", "Employee Name", "Company", "Department", "Designation", "Check Date",    "Check In",    "Check Out", "Official Status",    "Generated By",    "Generated Time"];
        } else if ($request->model == "Leave Type") {
            return ["Company Name", "Leave Name", "Payment Mode", "Total Day", "Monthly Limit"];
        } else if ($request->model == "Employee Leave Application (All)") {
            return ["Employee Id", "Employee Name", "Designation", "Leave Type", "From", "To", "Day", "Reason", "Recommender Comments", "Recommended By", "Approval Comments",  "Approve By", "Recomended Status", "Approval Status"];
        } else if ($request->model == "Cancel Leave Application") {
            return ["Company", "Employee Name", "Employee Id", "Leave Type", "From", "To", "Total Leave", "Available", "Apply For Day", "Reason"];
        } else if ($request->model == "Pending Approval Leave Application") {
            return ["Company", "Employee Id", "Employee Name", "Leave Type", "From", "To", "Total Leave", "Available", "Apply For Day", "Reason", "Recommended By"];
        } else if ($request->model == "Pending Recommend Leave Application") {
            return ["Company", "Employee Name", "Employee Id", "Leave Type", "From", "To", "Total Leave", "Available", "Apply For Day", "Reason"];
        } else if ($request->model == "Short Leave Application List") {
            return ["Company", "Employee Name", "Employee Id", "Department", "Designation", "Leave Type", "Leave Date", "From", "To", "In", "Reason"];
        } else if ($request->model == "Late Adjustment") {
            return ["Employee Id", "Employee Name", "Company", "Department", "Designation", "Effected Month", "Late Days", "Late Deduction Days"];
        } else if ($request->model == "Salary Increment Form") {
            return ["Employee Id", "Employee Name", "Department", "Designation", "Joining Date", "Joining Salary", "Current Salary", "Percent", "Increment", "Total"];
            $previous_years = [];
            for($i=$request->previous_year; $i<fdate($request->from_date, 'Y'); $i++) {
                $previous_years = array_merge($previous_years, ["Per", "Inc", "Salary"]);
            }
            
            $firstPart = ["Employee Id", "Employee Name", "Department", "Designation", "Joining Date", "Joining Salary"];
            $lastPart = ["Percent", "Increment", "Current Salary", "Percent", "Increment", "Total"];
            $firstPart = array_merge($firstPart, $previous_years);
            $headers = array_merge($firstPart, $lastPart);

        } else if ($request->model == "Bonus Create Form") {

            if(strtolower($request->employee_type) == 'production') {

                return ["Employee Id", "Employee Name", "Department", "Designation", "Joining Date (m-d-Y)", "Basic Salary", "Bonus Amount", "Bank Amount", "Cash Amount"];
            } else {
                return ["Employee Id", "Employee Name", "Department", "Designation", "Joining Date (m-d-Y)", "Joining Salary", "Current Salary", "Bonus Amount", "Bank Amount", "Cash Amount"];

            }
        }
    }



    //#########################################         methods for individual models        ###########################


    // Disbursement List Data
    public function disbursementList()
    {
        $mydata = [];
        $request = $this->request;
        $data = [];

        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();


        $data['disbursements']     = Disbursement::with('employee.department', 'employee.designation', 'disbursement_type')
            ->orderBy('employee_id');
        $data['disbursements']->whereHas('employee', function ($q) use ($request, $designations_id, $departments_id, $companies_id) {
            $q->whereIn('company_id', $companies_id)
                ->whereIn('department_id', $departments_id)
                ->whereIn('designation_id', $designations_id)
                ->where('status', 1);
        });

        if ($request->filled('company_id')) {
            $data['disbursements']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }
        if ($request->filled('employee_group_id')) {
            $data['disbursements']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_group_id', $request->employee_group_id  );
            });
        }
        if ($request->filled('department_id')) {
            $data['disbursements']->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('designation_id')) {
            $data['disbursements']->whereHas('employee', function ($q) use ($request) {
                $q->where('designation_id', $request->designation_id);
            });
        }
        if ($request->filled('disbursement_type_id')) {
            $data['disbursements']->where('disbursement_type_id', $request->disbursement_type_id);
        }
        if ($request->filled('employee_id')) {
            $data['disbursements']->whereHas('employee', function ($q) use ($request, $designations_id, $departments_id, $companies_id) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }
        if ($request->filled('from_month') && $request->filled('to_month')) {
            $data['disbursements']->whereBetween('effected_month', [$request->from_month, $request->to_month]);
        }

        foreach ($data['disbursements']->get() as $key => $disbursement) {
            $mydata['data_1'][] = $disbursement->effected_month;
            $mydata['data_2'][] = $disbursement->employee->employee_full_id;
            $mydata['data_3'][] = $disbursement->employee->name;
            $mydata['data_4'][] = $disbursement->employee->department->name;
            $mydata['data_5'][] = $disbursement->employee->designation->name;
            $mydata['data_6'][] = $disbursement->employee->joining_date;
            $mydata['data_7'][] = $disbursement->disbursement_type->name;
            $mydata['data_8'][] = $disbursement->total_value;
            $mydata['data_9'][] = $disbursement->disbursement_type_id == 2 ? $disbursement->ot_hour : '';
        }
        return $mydata;
    }

    // Disbursement Create Data
    public function disbursementCreate()
    {
        $mydata = [];
        $request = $this->request;
        $data = [];

        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();

        $data['employees'] = Employee::with('company.shift', 'current_salary', 'department', 'designation', 'bank_information')
            ->whereHas('salaries')
            ->orderByDesc('created_at')
            ->where('status', 1)
            ->whereIn('company_id', $companies_id)
            ->whereIn('department_id', $departments_id)
            ->whereIn('designation_id', $designations_id);

        if ($request->filled('company_id')) {
            $data['employees']->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $data['employees']->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $data['employees']->where('designation_id', $request->designation_id);
        }
        if ($request->filled('employee_id')) {
            $data['employees']->where('employee_full_id', $request->employee_id);
        }
        if ($request->filled('employee_group_id')) {
            $data['employees']->where('employee_group_id', $request->employee_id);
        }

        $data['employees'] = $data['employees']->get();

        foreach ($data['employees'] as $key => $employee) {
            $mydata['data_1'][] = $employee->employee_full_id;
            $mydata['data_2'][] = $employee->name;
            $mydata['data_3'][] = $employee->department ? $employee->department->name : '';
            $mydata['data_4'][] = $employee->designation ? $employee->designation->name : '';
            $mydata['data_5'][] = $employee->joining_date;
        }

        return $mydata;
    }

    // Disbursement List Data
    public function incrementList()
    {
        $mydata = [];
        $request = $this->request;

        $companies_id = Company::userCompanies()->keys();
        $departments_id = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();
        $relations = ['bank_salary', 'cash_salary', 'employee', 'employee.department', 'employee.designation'];
        $salaries    = Salary::with($relations)->orderBy('employee_id')->orderBy('effect_from_date')->where('approve', 1);

        if (!$request->filled('company_id') && !$request->filled('department_id') && !$request->filled('designation_id')) {
            $salaries->whereHas('employee', function ($q) use($companies_id, $departments_id, $designations_id) {
                 $q->whereIn('company_id', $companies_id)
                     ->whereIn('department_id', $departments_id)
                     ->whereIn('designation_id', $designations_id);
            });
        } else {
            if ($request->filled('company_id')) {
                $salaries->whereHas('employee', function ($q) use($request) {
                    $q->where('company_id', $request->company_id);
                });
            }
            if ($request->filled('department_id')) {
                $salaries->whereHas('employee', function ($q) use($request) {
                    $q->where('department_id', $request->department_id);
                });
            }
            if ($request->filled('designation_id')) {
                $salaries->whereHas('employee', function ($q) use($request) {
                    $q->where('designation_id', $request->designation_id);
                });
            }
            if ($request->filled('employee_group_id')) {
                $salaries->whereHas('employee', function ($q) use($request) {
                    $q->where('employee_group_id', $request->employee_group_id);
                });
            }
            $salaries->where('effect_from_date', '>=', $request->from_month)->where('effect_from_date', '<=', $request->to_month);
        }



        foreach ($salaries->get() as $key => $salary) {
            $bank_salary = optional($salary->bank_salary)->gross;
            $cash_salary = optional($salary->cash_salary)->gross;

            $mydata['data_1'][] = $salary->employee->employee_full_id;
            $mydata['data_2'][] = $salary->employee->name;
            $mydata['data_3'][] = $salary->employee->department->name;
            $mydata['data_4'][] = $salary->employee->designation->name;
            $mydata['data_5'][] = fdate($salary->employee->joining_date, 'm-d-Y');
            $mydata['data_6'][] = Carbon::parse($salary->effect_from_date)->format('Y');
            $mydata['data_7'][] = round($salary->increment) > 0 ? round($salary->increment) : '';
            $mydata['data_8'][] = $salary->percent > 0 ? $salary->percent : '';
            $mydata['data_9'][] = $salary->effect_from_date;
            $mydata['data_10'][] = round($salary->total_gross);
            $mydata['data_11'][] = round($bank_salary) > 0 ? round($bank_salary) : '';
            $mydata['data_12'][] = round($cash_salary) > 0 ? round($cash_salary) : '';
        }

        return $mydata;
    }


    // Employee List
    public function employeeList()
    {
        $mydata = [];
        $request = $this->request;
        $data = [];

        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();

        $relation = ['company', 'department:name,id', 'designation:name,id', 'grade', 'educational_qualification', 'experience', 'personal_information', 'guardian', 'reference_person', 'bank_information.company_bank'];

        $data['employees'] = Employee::with($relation)
                            ->orderByDesc('created_at')
                            ->whereIn('company_id', $companies_id)
                            ->whereIn('department_id', $departments_id)
                            ->whereIn('designation_id', $designations_id);


        if ($request->filled('company')) {
            $data['employees']->where('company_id', $request->company);
        }
        if ($request->filled('department')) {
            $data['employees']->where('department_id', $request->department);
        }

        if ($request->filled('designation')) {
            $data['employees']->where('designation_id', $request->designation);
        }
        if ($request->filled('grade')) {
            $data['employees']->where('grade_id', $request->grade);
        }
        if ($request->filled('line')) {
            $data['employees']->where('line_id', $request->line);
        }
        if ($request->filled('employee_type')) {
            $data['employees']->where('employee_type', $request->employee_type);
        }
        if ($request->filled('employee_id')) {
            $data['employees']->where('employee_full_id', $request->employee_id);
        }
        if ($request->filled('employee_group_id')) {
            $data['employees']->where('employee_group_id', $request->employee_group_id);
        }

        if ($request->filled('salary_type')){
            $data['employees']->whereHas('salaries', function ($q) use($request) {
                $q->where('salary_type_id', $request->salary_type);
            });

        }

        if ($request->bank == 1) {
            $data['employees']->whereHas('bank_information');
        }

        if ($request->filled('status')) {
            $data['employees']->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $data['employees']->whereDate('updated_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $data['employees']->whereDate('updated_at', '<=', $request->to_date);
        }

        foreach ($data['employees']->get() as $key => $employee) {
         
            if ($request->routeIs('export.as.pdf')) {

                $status = $employee->status == 1 ? '<span class="label label-success">Activated</span>' : '<span class="label label-danger">De-activated</span>';

                $mydata['data_1'][] = $employee->id_number . $employee->given_id_number;
                $mydata['data_2'][] = $employee->name;
                $mydata['data_3'][] = $employee->joining_date;
                $mydata['data_4'][] = $employee->getCompanyName();
                $mydata['data_5'][] = $employee->getDepartmentName() ;
                $mydata['data_6'][] = $employee->getDesignationName();
                $mydata['data_7'][] = $employee->card_no;
                $mydata['data_8'][] = $status;
                
            } else  {
                $mydata['data_1'][] = $key + 1;
                $mydata['data_2'][] = optional($employee->company)->name;
                $mydata['data_3'][] = $employee->name;
                $mydata['data_4'][] = $employee->id_number . $employee->given_id_number;
                $mydata['data_5'][] = $employee->previous_id_number;
                $mydata['data_6'][] = $employee->joining_date;
                $mydata['data_7'][] = optional($employee->department)->name;
                $mydata['data_8'][] = optional($employee->designation)->name;
                $mydata['data_9'][] = optional($employee->grade)->name;
                $mydata['data_10'][] = optional($employee->line)->name;
                $mydata['data_11'][] = $employee->father_or_husband_name;
                $mydata['data_12'][] = $employee->mother_name;
                $mydata['data_13'][] = $employee->date_of_birth;
                $mydata['data_14'][] = $employee->gender;
                $mydata['data_15'][] = $employee->marital_status;
                $mydata['data_16'][] = $employee->religion;
                $mydata['data_17'][] = $employee->present_address;
                $mydata['data_18'][] = $employee->present_phone_number;
                $mydata['data_19'][] = $employee->permanent_address;
                $mydata['data_20'][] = $employee->permanent_phone_number;
                $mydata['data_21'][] = $employee->email;
                $mydata['data_22'][] = $employee->nationality;
                $mydata['data_23'][] = $employee->national_id;
                $mydata['data_24'][] = $employee->employee_type;
                $mydata['data_25'][] = $employee->p_bonus_type;
                $mydata['data_26'][] = $employee->device_id;
                $mydata['data_27'][] = $employee->finger_print_id;
                $mydata['data_28'][] = optional($employee->salary)->total_gross;
                $mydata['data_29'][] = $employee->mfs_type;
                $mydata['data_30'][] = $employee->mfs;
                $mydata['data_31'][] = $employee->card_no;
                $mydata['data_32'][] = optional($employee->personal_information)->height ?: '';
                $mydata['data_33'][] = optional($employee->personal_information)->weight ?: '';
                $mydata['data_34'][] = optional($employee->personal_information)->phone_no ?: '';
                $mydata['data_35'][] = optional($employee->personal_information)->blood_group;
                $mydata['data_36'][] = optional($employee->guardian)->name;
                $mydata['data_37'][] = optional($employee->guardian)->phone_no_1;
                $mydata['data_38'][] = optional($employee->guardian)->phone_no_2;
                $mydata['data_39'][] = optional($employee->guardian)->relation;
                $mydata['data_40'][] = optional($employee->guardian)->address;
                $mydata['data_41'][] = optional($employee->reference_person)->name;
                $mydata['data_42'][] = optional($employee->reference_person)->phone_no_1;
                $mydata['data_43'][] = optional($employee->reference_person)->phone_no_2;
                $mydata['data_44'][] = optional($employee->reference_person)->relation;
                $mydata['data_45'][] = optional($employee->reference_person)->address;
                $mydata['data_46'][] = optional(optional($employee->bank_information)->company_bank)->bank_name;
                $mydata['data_47'][] = optional($employee->bank_information)->bank_account_no;
                $mydata['data_48'][] = optional($employee->bank_information)->e_tin_number;
                $mydata['data_49'][] = optional($employee->last_educational_qualification())->examination;
                $mydata['data_50'][] = optional($employee->last_educational_qualification())->number;
                $mydata['data_51'][] = optional($employee->last_educational_qualification())->passing_year;
                $mydata['data_52'][] = optional($employee->last_educational_qualification())->board;
                $mydata['data_53'][] = optional($employee->lastExperience())->designation;
                $mydata['data_54'][] = optional($employee->lastExperience())->duration;
                $mydata['data_55'][] = $employee->spouse_name;
                $mydata['data_56'][] = $employee->employment_status;
            }
        }

        return $mydata;
    }


    // He Loan List
    public function hrLoanList()
    {
        $mydata  = [];
        $data    = [];
        $request = $this->request;

        $companies_id    = Company::userCompanyId();
        $departments_id  = Department::userDepartmentId();
        $designations_id = Designation::userDesignationId();

        $relations = ['employee', 'employee.department', 'employee.designation', 'loan_details', 'created_user', 'updated_user'];
        $data['loans']   = HrLoan::with($relations)
            ->orderBy('approval_receive_on')
            ->whereHas('employee', function ($q) use ($request) {
                $q->where('status', 1);
            })
            ->withCount(['loan_details' => function ($q) {
                $q->where('status', 1);
            }]);


        if ($request->filled('company_id')) {
            $data['loans']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
            if ($request->filled('department_id')) {
                $data['loans']->whereHas('employee', function ($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }
            if ($request->filled('designation_id')) {
                $data['loans']->whereHas('employee', function ($q) use ($request) {
                    $q->where('designation_id', $request->designation_id);
                });
            }
            if ($request->filled('employee_id')) {
                $data['loans']->whereHas('employee', function ($q) use ($request) {
                    $q->where('employee_full_id', $request->employee_id);
                });
            }
            if ($request->filled('employee_group_id')) {
                $data['loans']->whereHas('employee', function ($q) use ($request) {
                    $q->where('employee_group_id', $request->employee_group_id);
                });
            }
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data['loans']->whereBetween('approval_receive_on', [$request->from_date, $request->to_date]);
            }
        } else {
            $data['loans']->whereHas('employee', function ($q) use ($companies_id, $departments_id, $designations_id) {
                $q->whereIn('company_id', $companies_id)->whereIn('department_id', $departments_id)->whereIn('designation_id', $designations_id);
            });
        }

        foreach ($data['loans']->get() as $key => $loan) {
            $mydata['data_1'][] = $loan->employee->employee_full_id;
            $mydata['data_2'][] = $loan->employee->name;
            $mydata['data_3'][] = $loan->employee->department->name;
            $mydata['data_4'][] = $loan->employee->designation->name;
            $mydata['data_5'][] = $loan->approval_receive_on;
            $mydata['data_6'][] = $loan->amount;
            $mydata['data_7'][] = $loan->installment;
            $mydata['data_8'][] = $loan->loan_details_count . '/' . $loan->installment;
            $mydata['data_9'][] = ($loan->amount / $loan->installment) * $loan->loan_details_count;
            $mydata['data_10'][] = $loan->amount - ($loan->loan_details_count * ($loan->amount / $loan->installment));
            $mydata['data_11'][] = $loan->is_approved ? 'Approved' : 'Not Approved';
        }
        return $mydata;
    }



    // Salary List
    public function salaryList()
    {
        $mydata = [];
        $request = $this->request;
        $data = [];



        $relation = ['company', 'employee.grade', 'employee.department', 'employee.designation', 'salary_type', 'bank_salary', 'cash_salary'];

        $data['salaries'] = Salary::with($relation)
            ->whereHas('employee', function ($q) {
                $q->searchCompany()
                ->searchDepartment()
                ->searchDesignation()
                ->permissionEmployment()
                ->where('status', 1);
            })
            ->where('company_id', auth()->user()->company_id)
            ->where('status', '>', 0)
            ->orderBy('id', 'desc');


        if ($request->filled('grade_id)')) {
            $data['salaries']->whereHas('employee', function ($q) use ($request) {
                $q->where('grade_id', $request->get('grade_id'));
            });
        }


        if ($request->get('employee_name') && $request->get('employee') != '') {
            $data['salaries']->whereHas('employee', function ($q) use ($request) {
                $q->where('designation_id', $request->get('designation'));
            });
        }


        if ($request->get('employee_name') && $request->get('employee_name') != '') {
            $data['salaries']->whereHas('employee', function ($q) use ($request) {
                $q->where('name', $request->get('employee_name'));
            });
        }
        if ($request->get('employee_id_number') && $request->get('employee_id_number') != '') {
            $data['salaries']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->get('employee_id_number'));
            });
        }

        foreach ($data["salaries"]->get() as $key => $salary) {
            $mydata['data_1'][] = $salary->employee->employee_full_id;
            $mydata['data_2'][] = $salary->employee->name;
            $mydata['data_3'][] = $salary->employee->grade ? $salary->employee->grade->grade_name : '';
            $mydata['data_4'][] = $salary->employee->getDepartmentName();
            $mydata['data_5'][] = $salary->employee->getDesignationName();
            $mydata['data_6'][] = date('F d, Y', strtotime($salary->employee->joining_date));
            $mydata['data_7'][] = $salary->permanent_date ? date('F d, Y', strtotime($salary->permanent_date)) : '';
            $mydata['data_8'][] = date('F d, Y', strtotime($salary->effect_from_date));
            $mydata['data_9'][] = $salary->increment;
            $mydata['data_10'][] = $salary->total_gross;
        }
        return $mydata;
    }

    // Department List
    public function departmentList()
    {
        $mydata = [];
        $departments =  auth()->id() == 1 ? Department::orderBydesc('id')->get() : auth()->user()->departments->orderBydesc('id')->get();

        foreach ($departments as $key => $department) {
            $mydata['data_1'][] = $department->name;
            $mydata['data_2'][] = $department->code;
            $mydata['data_3'][] = \Carbon\Carbon::parse($department->created_at)->format('F d, Y h:i s A');
            $mydata['data_4'][] = \Carbon\Carbon::parse($department->updated_at)->format('F d, Y h:i s A');
        }
        return $mydata;
    }

    // Designation List
    public function designationList()
    {
        $mydata = [];
        $designations =  auth()->id() == 1 ? Designation::orderBydesc('id')->get() : auth()->user()->designations->orderBydesc('id')->get();

        foreach ($designations as $key => $designation) {
            $mydata['data_1'][] = $designation->name;
            $mydata['data_2'][] = $designation->code;
            $mydata['data_3'][] = \Carbon\Carbon::parse($designation->created_at)->format('F d, Y h:i s A');
            $mydata['data_4'][] = \Carbon\Carbon::parse($designation->updated_at)->format('F d, Y h:i s A');
        }
        return $mydata;
    }

    // Grade List
    public function gradeList()
    {
        $mydata = [];
        $grades =  Grade::orderBy('grade_name')->get();

        foreach ($grades as $key => $grade) {
            $mydata['data_1'][] = $grade->grade_name;
            $mydata['data_2'][] = $grade->grade_id;
            $mydata['data_3'][] = \Carbon\Carbon::parse($grade->created_at)->format('F d, Y h:i s A');
            $mydata['data_4'][] = \Carbon\Carbon::parse($grade->updated_at)->format('F d, Y h:i s A');
        }
        return $mydata;
    }

    // Line List
    public function lineList()
    {
        $mydata = [];
        $lines =  Line::query()->orderBy('name')->get();

        foreach ($lines as $key => $line) {
            $mydata['data_1'][] = $line->name;
            $mydata['data_2'][] = \Carbon\Carbon::parse($line->created_at)->format('F d, Y h:i s A');
            $mydata['data_3'][] = \Carbon\Carbon::parse($line->updated_at)->format('F d, Y h:i s A');
        }
        return $mydata;
    }

    // Bonus List
    public function bonusList()
    {
        $data   = [];
        $request  = $this->request;

        $relation = ['bonus_type', 'bonus_details', 'company', 'department', 'approve_by', 'created_user'];

        $bonuses = FixedBonus::with($relation)
                                        ->where('is_approved', 1)
                                        ->orderByDesc('id')
                                        ->whereIn('company_id', Company::userCompanyId())
                                        ->where(function($q) {
                                            $q->whereIn('department_id', Department::userDepartmentId())
                                            ->orWhere('department_id', null);
                                        })
                                        ->searchByField('company_id')
                                        ->searchByField('department_id')
                                        ->searchByField('bonus_type_id')
                                        ->when($request->filled('employee_type'), function($q) use($request){
                                            $q->whereHas('bonus_details.employee', function($qr) use($request) {
                                                $qr->where('employee_type', $request->employee_type);
                                            });
                                        })
                                        ->when($request->filled('year'), function($q) use($request) {
                                            $q->where('effected_from', '>=', $request->year . '-00');
                                        })
                                        ->get();

        foreach ($bonuses as $key => $bonus) {
            $designations = [];
            foreach ($bonus->bonus_details as $index => $designation) {
                if (!in_array($designation->employee->designation->name, $designations, true)) {
                    array_push($designations, $designation->employee->designation->name);
                }
            }

            $data['data_1'][] = \Carbon\Carbon::parse($bonus->effected_from)->format('M, Y');
            $data['data_2'][] = $bonus->bonus_details->count();
            $data['data_3'][] = $bonus->bonus_type->type;
            $data['data_4'][] = $bonus->company->name;
            $data['data_5'][] = optional($bonus->department)->name;
            $data['data_6'][] = $designations[0];
            $data['data_7'][] = $bonus->payment_type;
            $data['data_8'][] = number_format($bonus->total_amount);
            $data['data_9'][] = number_format($bonus->total_bank_amount);
            $data['data_10'][] = number_format($bonus->total_cash_amount);
            $data['data_11'][] = $bonus->created_user->name . ' ' . Carbon::parse($bonus->created_at)->format('Y-m-d');
            $data['data_12'][] = $bonus->approve_by->name . '  ' . Carbon::parse($bonus->approve_at)->format('Y-m-d');
        }
        return $data;
    }

    // Bonus Disbursement List
    public function bonusDisbursementList()
    {
        $data     = [];
        $mydata   = [];
        $request  = $this->request;
        $relation = ['bonus_type', 'bonus_details', 'company', 'department', 'approve_by'];

        $data['companies']    = Company::userCompanies();
        $data['departments']  = Department::userDepartments();


        $department_ids         = $data['departments']->keys();
        $designations_id        = Designation::userDesignationId();
        $designations_id = Designation::userDesignationId();

        $relation = ['bonus_type', 'bonus_details', 'company', 'department'];
        $data['bonuses'] = FixedBonus::with($relation)
        ->where('is_approved', 0)
        ->orderByDesc('id')
        ->searchByField('company_id')
        ->searchByField('department_id')
        ->searchByField('bonus_type_id')
        ->searchByField('effected_from')
        ->searchByField('employee_type')
        ->whereIn('company_id', $data['companies']->keys())
        ->where(function($q) use($department_ids) {
            $q->whereIn('department_id', $department_ids)
            ->orWhere('department_id', null);
        })
        
        ->whereHas('bonus_details.employee.active_employment', function ($q) use ($designations_id) {
            $q->whereIn('designation_id', $designations_id);
        })->get();

        foreach ($data['bonuses'] as $key => $bonus) {

            $mydata['data_1'][] = \Carbon\Carbon::parse($bonus->effected_from)->format('F, Y');
            $mydata['data_2'][] = $bonus->bonus_details->count();
            $mydata['data_3'][] = $bonus->bonus_type->type;
            $mydata['data_4'][] = $bonus->company->name;
            $mydata['data_5'][] = optional($bonus->department)->name ?? 'All Department';
            $mydata['data_6'][] = $bonus->payment_type;
            $mydata['data_7'][] = number_format($bonus->total_amount);
            $mydata['data_8'][] = number_format($bonus->total_bank_amount);
            $mydata['data_9'][] = number_format($bonus->total_cash_amount);
        }
        return $mydata;
    }



    // // Bonus Detail List
    public function bonusDetailList()
    {
        $mydata = [];

        $fixedBonus = FixedBonus::find($this->request->bonus_id);
        
        $basic_rate_setup = BasicRateSetup::orderByDesc('effected_month')
        ->where('company_id', $fixedBonus->company_id)
        ->where('department_id', $fixedBonus->department_id)
        ->where('effected_month', '<=', $fixedBonus->effected_from)->first();

        $bonus_details = FixedBonusDetail::with('employee.joining_salary')->employment()->where('fixed_bonus_id', $this->request->bonus_id)->orderBy('id')->get();


        foreach ($bonus_details as $key => $details) {
            $mydata['data_1'][] = $details->employee->employee_full_id;
            $mydata['data_2'][] = $details->employee->name;
            $mydata['data_3'][] = $details->getDepartmentName();
            $mydata['data_4'][] = $details->getDesignationName();
            $mydata['data_5'][] = fdate($details->employee->joining_date, 'm-d-Y');

            if(strtolower($details->employee->employee_type) == "production") {
                $mydata['data_6'][] = optional($basic_rate_setup)->rate;
                $mydata['data_7'][] = number_format($details->bonus_amount) ?: '';
                $mydata['data_8'][] = "";
                $mydata['data_9'][] = "";
            } else {
                $mydata['data_6'][] = number_format(round($details->employee->current_salary->total_gross));
                $mydata['data_7'][] = number_format($details->bonus_amount) ?: '';
                $mydata['data_8'][] = number_format($details->bank_amount) ?: '';
                $mydata['data_9'][] = number_format($details->cash_amount) ?: '';
                $mydata['data_10'][] = "";
                $mydata['data_11'][] = "";
            }
        }
        return $mydata;
    }

    // // Bonus Detail List
    public function attendanceBonusList()
    {
        $mydata = [];
        $request = $this->request;
        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();


        $attendanceBonusList = AttendanceBonusDetails::with('attendance_bonus', 'employee', 'employee.company', 'employee.department', 'employee.designation')->whereHas('employee', function ($q) use ($request, $departments_id, $companies_id) {
            $q->whereIn('company_id', $companies_id)->whereIn('department_id', $departments_id);
        })->orderByDesc('id');

        if ($request->filled('company')) {
            $attendanceBonusList->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
        }
        if ($request->filled('department')) {
            $attendanceBonusList->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }
        if ($request->filled('employee_id')) {
            $attendanceBonusList->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }
        if ($request->filled('from_month') && $request->filled('to_month')) {
            $attendanceBonusList->whereHas('attendance_bonus', function ($q) use ($request) {
                $q->whereBetween('applicable_month', [$request->from_month, $request->to_month]);
            });
        }

        $attendance_bonus_type = ["", "Manual", "Auto"];

        foreach ($attendanceBonusList->get() as $key => $attendanceBonus) {
            $mydata['data_1'][] = $attendanceBonus->employee->employee_full_id;
            $mydata['data_2'][] = $attendanceBonus->employee->name;
            $mydata['data_3'][] = $attendanceBonus->employee->company->name;
            $mydata['data_4'][] = $attendanceBonus->employee->department->name;
            $mydata['data_5'][] = $attendanceBonus->employee->designation->name;
            $mydata['data_6'][] = $attendance_bonus_type[$attendanceBonus->attendance_bonus->bonus_type];
            $mydata['data_7'][] = $attendanceBonus->attendance_bonus->applicable_month;
            $mydata['data_8'][] = $attendanceBonus->att_bonus;
            $mydata['data_9'][] = $attendanceBonus->status == 1 ? "Generated" : "Not Generated";
        }
        return $mydata;
    }


    // out work list
    public function outWorkList()
    {

        $data = [];
        $request = $this->request;

        $outsideWorks = OutsideWork::orderByDesc('date')
                    ->with('employee')
                    ->employment()
                    ->searchCompany()
                    ->searchDesignation()
                    ->searchDepartment()
                    ->permissionEmployment()
                    ->searchFromRelation('employee', 'employee_full_id')
                    ->when($request->filled('from_date') && $request->filled('to_date'), function($q) use($request) {
                        $q->whereBetween('date', [$request->from_date, $request->to_date]);
                    })
                    ->get();

       

        foreach ($outsideWorks as $key => $outside_work) {
            $mydata['data_1'][] = $outside_work->employee->employee_full_id;
            $mydata['data_2'][] = $outside_work->employee->name;
            $mydata['data_3'][] = $outside_work->getCompanyName();
            $mydata['data_4'][] = $outside_work->created_at;
            $mydata['data_5'][] = $outside_work->date;
            $mydata['data_6'][] = $outside_work->return_time;
            $mydata['data_7'][] = $outside_work->actual_return_time;
            $mydata['data_8'][] = $outside_work->note;
        }
        
        return $mydata;
    }

    // Manual All Attendance List
    public function manualAllAttendanceList()
    {
        $data = [];
        $request = $this->request;

        $attendances = Attendance::with('employee', 'created_user')
                                        ->latest('date')
                                        ->whereHas('employee')
                                        ->employment()
                                        ->searchCompany()
                                        ->searchDepartment()
                                        ->searchDesignation()
                                        ->searchFromRelation('employee', 'shift_id')
                                        ->searchFromRelation('employee', 'employee_full_id')
                                        ->when($request->filled('month'), function($q) use($request) {
                                            $q->where(DB::raw('substr(date, 1, 7)'), ($request->month ?? Date('Y-m')));
                                        })
                                        ->get();

        foreach ($attendances as $attendance) {
            $data['data_1'][]   = $attendance->employee->employee_full_id;
            $data['data_2'][]   = $attendance->employee->name;
            $data['data_3'][]   = $attendance->getCompanyName();
            $data['data_4'][]   = $attendance->getDepartmentName();
            $data['data_5'][]   = $attendance->getDesignationName();
            $data['data_6'][]   = $attendance->date;
            $data['data_7'][]   = $attendance->check_in_time;
            $data['data_8'][]   = $attendance->check_out_time;
            $data['data_9'][]   = $attendance->status == 1 ? 'In Office' : '';
            $data['data_10'][]  = optional($attendance->created_user)->name;
            $data['data_11'][]  = $attendance->created_at;
        }
        
        return $data;
    }

    // Leave Type List
    public function leaveTypeList()
    {
        $mydata  = [];
        $request = $this->request;

        $companies_id = Company::userCompanies()->keys();
        $leaveTypes   = LeaveType::with('company');

        if ($request->filled('company_id')) {
            $leaveTypes->where('company_id', $request->company_id);
        } else {
            $leaveTypes->whereIn('company_id', $companies_id);
        }
        if ($request->filled('leave_type')) {
            $leaveTypes->where('name', $request->leave_type);
        }
        if ($request->filled('payment_mode')) {
            $leaveTypes->where('payment_mode', $request->payment_mode);
        }

        foreach ($leaveTypes->get() as $key => $leaveType) {
            $mydata['data_1'][] = $leaveType->company->name;
            $mydata['data_2'][] = $leaveType->name;
            $mydata['data_3'][] = $leaveType->payment_mode == "wp" ? "With Pay" : "With Out Pay";
            $mydata['data_4'][] = $leaveType->total_days;
            $mydata['data_5'][] = $leaveType->monthly_limit;
        }
        return $mydata;
    }

    // Leave Application List
    public function leaveApplicationList()
    {
        $mydata  = [];
        $request = $this->request;

        $data = [];
        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();

        $data['leave_applications'] = LeaveApplication::with('company', 'employee', 'leave_type')
            ->whereHas('employee', function ($q) use ($request, $companies_id, $departments_id, $designations_id) {
                $q->whereIn('company_id', $companies_id)->whereIn('department_id', $departments_id)->whereIn('designation_id', $designations_id)->where('status', 1);
            });

        if ($request->filled('company')) {
            $data['leave_applications']->where('company_id', $request->company);
            // $data['leave_types'] = LeaveType::where('company_id', $request->company)->select('name', 'id', 'payment_mode')->get();
        }

        if ($request->filled('leave_type')) {
            $data['leave_applications']->where('leave_type_id', $request->leave_type);
        }

        if ($request->filled('recommend')) {
            if ($request->recommend == 1) {
                $data['leave_applications']->where('recommended_by', '!=', '');
            }
            if ($request->recommend == 2) {
                $data['leave_applications']->where('recommended_by', null);
            }
        }

        if ($request->filled('approval')) {
            if ($request->approval == 1) {
                $data['leave_applications']->where('approved_by', '!=', '');
            }
            if ($request->approval == 2) {
                $data['leave_applications']->where('approved_by', null);
            }
        }

        if ($request->filled('from') && $request->filled('to')) {
            $data['leave_applications']->where('from', '>=', $request->from)->where('to', '<=', $request->to);
        }

        if ($request->filled('employee_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }
        if ($request->filled('employee_group_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_group_id', $request->employee_group_id);
            });
        }

        foreach ($data['leave_applications']->get() as $key => $leave_application) {
            $from = \Carbon\Carbon::parse($leave_application->from);
            $to = \Carbon\Carbon::parse($leave_application->to);
            $totalDay = $to->diffInDays($from);

            $mydata['data_1'][] = $leave_application->employee->name;
            $mydata['data_2'][] = $leave_application->employee->employee_full_id;
            $mydata['data_3'][] = $leave_application->employee->designation->name;
            $mydata['data_4'][] = $leave_application->leave_type->name . $leave_application->leave_type->payment_mode == 'wp' ? ' With Pay' : ' With Out Pay';
            $mydata['data_5'][] = $leave_application->from;
            $mydata['data_6'][] = $leave_application->to;
            $mydata['data_7'][] = $totalDay + 1;
            $mydata['data_8'][] = $leave_application->from;
            $mydata['data_9'][] = str_limit($leave_application->reason, 20, '...');
            $mydata['data_10'][] = $leave_application->recommendation_note;
            $mydata['data_11'][] = optional($leave_application->recommender)->name;
            $mydata['data_12'][] = $leave_application->approval_note;
            $mydata['data_13'][] = optional($leave_application->approved)->name;
            $mydata['data_14'][] = $leave_application->recommended_by != null ? "Recommended" : "Waiting";
            $mydata['data_15'][] = $leave_application->approved_by != null ? "Approved" : "Waiting";
        }
        return $mydata;
    }

    // cancel Leave Application
    public function applicationCancelList()
    {
        $mydata  = [];
        $request = $this->request;
        $data    = [];


        $data['leave_applications'] = LeaveApplication::with('company', 'employee', 'leave_type')
            ->where('cancel', 1);

        if ($request->filled('company')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
            $data['leave_types'] = LeaveType::where('company_id', $request->company)->select('name', 'id', 'payment_mode')->get();
        }
        if ($request->filled('leave_type')) {
            $data['leave_applications']->where('leave_type_id', $request->leave_type);
        }
        if ($request->filled('recommend')) {
            if ($request->recommend == 1) {
                $data['leave_applications']->where('recommended_by', '!=', '');
            } else {
                $data['leave_applications']->where('recommended_by', null);
            }
        }
        if ($request->filled('approval')) {
            if ($request->approval == 1) {
                $data['leave_applications']->where('approved_by', '!=', '');
            }
            if ($request->approval == 2) {
                $data['leave_applications']->where('approved_by', null);
            }
        }

        if ($request->filled('from') && $request->filled('to')) {
            $data['leave_applications']->where('from', '=', $request->from)->where('to', '<=', $request->to);
        }

        if ($request->filled('employee_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id)->where('status', 1);
            });
        }

        foreach ($data['leave_applications']->get() as $key => $application) {
            $from = \Carbon\Carbon::parse($application->from);
            $to = \Carbon\Carbon::parse($application->to);
            $totalDay = $to->diffInDays($from);

            $mydata['data_1'][] = $application->employee->name;
            $mydata['data_2'][] = $application->employee->employee_full_id;
            $mydata['data_3'][] = $application->employee->designation->name;
            $mydata['data_4'][] = $application->leave_type->name;
            $mydata['data_5'][] = $application->from;
            $mydata['data_6'][] = $application->to;
            $mydata['data_7'][] = $application->leave_type->total_days;
            $mydata['data_8'][] = $application->leave_type->total_days - $totalDay;
            $mydata['data_9'][] = $totalDay + 1;
            $mydata['data_10'][] = str_limit($application->reason, 20, '...');
        }
        return $mydata;
    }


    // pending approval application list
    public function pendingApprovalApplicationList()
    {
        $mydata  = [];
        $request = $this->request;
        $data    = [];


        $data['leave_applications'] = LeaveApplication::with('company', 'employee', 'leave_type')
            ->where('recommended_by', '!=', "")
            ->where('cancel', 0);

        if ($request->filled('company')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
            $data['leave_types'] = LeaveType::where('company_id', $request->company)->select('name', 'id', 'payment_mode')->get();
        }
        if ($request->filled('leave_type')) {
            $data['leave_applications']->where('leave_type_id', $request->leave_type);
        }
        if ($request->filled('recommend')) {
            if ($request->recommend == 1) {
                $data['leave_applications']->where('recommended_by', '!=', '');
            } else {
                $data['leave_applications']->where('recommended_by', null);
            }
        }
        if ($request->filled('approval')) {
            if ($request->approval == 1) {
                $data['leave_applications']->where('approved_by', '!=', '');
            }
            if ($request->approval == 2) {
                $data['leave_applications']->where('approved_by', null);
            }
        }

        if ($request->filled('from') && $request->filled('to')) {
            $data['leave_applications']->where('from', '>=', $request->from)->where('to', '<=', $request->to);
        }

        if ($request->filled('employee_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }


        foreach ($data['leave_applications']->get() as $key => $application) {
            $from = \Carbon\Carbon::parse($application->from);
            $to = \Carbon\Carbon::parse($application->to);
            $totalDay = $to->diffInDays($from);

            $mydata['data_1'][] = $application->employee->company->name;
            $mydata['data_2'][] = $application->employee->employee_full_id;
            $mydata['data_3'][] = $application->employee->name;
            $mydata['data_4'][] = $application->leave_type->name;
            $mydata['data_5'][] = $application->from;
            $mydata['data_6'][] = $application->to;
            $mydata['data_7'][] = $application->leave_type->total_days;
            $mydata['data_8'][] = $application->leave_type->total_days - $totalDay;
            $mydata['data_9'][] = $totalDay + 1;
            $mydata['data_10'][] = str_limit($application->reason, 20, '...');
            $mydata['data_11'][] = $application->recommender->name;
        }
        return $mydata;
    }


    // pending Recommend Application List
    public function pendingRecommendApplicationList()
    {
        $mydata  = [];
        $request = $this->request;
        $data    = [];


        $usersDesig = optional(auth()->user()->employee)->designation_id;
        $usersCompany = optional(auth()->user()->employee)->company_id;

        $applicantDesig = ApplicantDesignation::with('recommender_companies', 'recommender_companies.recommender_designations')
            ->whereHas('recommender_companies', function ($q) use ($usersCompany, $usersDesig) {
                $q->whereHas('recommender_designations', function ($sQ) use ($usersDesig) {
                    $sQ->where('recommender_designation_id', $usersDesig);
                })->where('company_id', $usersCompany);
            })->pluck('applicant_designation_id');

        $data['leave_applications'] = LeaveApplication::with('company', 'employee', 'employee.company', 'leave_type')
            ->whereHas('employee', function ($q) use ($applicantDesig) {
                $q->whereIn('designation_id', $applicantDesig);
            })
            ->where('recommended_by', '=', null)
            ->where('cancel', 0)
            ->orderByDesc('employee_id');


        if ($request->filled('company')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });
            $data['leave_types'] = LeaveType::where('company_id', $request->company)->select('name', 'id', 'payment_mode')->get();
        }
        if ($request->filled('leave_type')) {
            $data['leave_applications']->where('leave_type_id', $request->leave_type);
        }
        if ($request->filled('recommend')) {
            if ($request->recommend == 1) {
                $data['leave_applications']->where('recommended_by', '!=', '');
            }
        }
        if ($request->filled('approval')) {
            if ($request->approval == 1) {
                $data['leave_applications']->where('approved_by', '!=', '');
            }
            if ($request->approval == 2) {
                $data['leave_applications']->where('approved_by', null);
            }
        }

        if ($request->filled('from') && $request->filled('to')) {
            $data['leave_applications']->where('from', '>=', $request->from)->where('to', '<=', $request->to);
        }

        if ($request->filled('employee_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }
        if ($request->filled('employee_group_id')) {
            $data['leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_group_id', $request->employee_group_id);
            });
        }

        foreach ($data['leave_applications']->get() as $key => $application) {
            $from = \Carbon\Carbon::parse($application->from);
            $to = \Carbon\Carbon::parse($application->to);
            $totalDay = $to->diffInDays($from);

            $mydata['data_1'][] = $application->employee->name;
            $mydata['data_2'][] = $application->employee->employee_full_id;
            $mydata['data_3'][] = $application->employee->designation->name;
            $mydata['data_4'][] = $application->leave_type->name;
            $mydata['data_5'][] = $application->from;
            $mydata['data_6'][] = $application->to;
            $mydata['data_7'][] = $application->leave_type->total_days;
            $mydata['data_8'][] = $application->leave_type->total_days - $totalDay;
            $mydata['data_9'][] = $totalDay + 1;
            $mydata['data_10'][] = str_limit($application->reason, 20, '...');
        }
        return $mydata;
    }

    // short Leave Application List
    public function shortLeaveApplicationList()
    {
        $mydata  = [];
        $request = $this->request;
        $data    = [];


        $usersDesig = optional(auth()->user()->employee)->designation_id;
        $usersCompany = optional(auth()->user()->employee)->company_id;

        $applicantDesig = SLeaveAppDesig::with('s_leave_companies', 's_leave_companies.s_leave_recom_desigs')
            ->whereHas('s_leave_companies', function ($q) use ($usersCompany, $usersDesig) {
                $q->whereHas('s_leave_recom_desigs', function ($sQ) use ($usersDesig) {
                    $sQ->where('recommender_designation_id', $usersDesig);
                })->where('company_id', $usersCompany);
            })->pluck('applicant_designation_id');


        $data['short_leave_applications'] = ShortLeaveApplication::with(
            'employee',
            'employee.company',
            'employee.department',
            'employee.designation',
            'employee.shortLeaveCount'
        )
            ->whereHas('employee', function ($q) use ($applicantDesig) {
                $q->whereIn('designation_id', $applicantDesig);
            })
            ->where('adjust', 0)
            ->orderByDesc('employee_id');


        if ($request->filled('company')) {
            $data['short_leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('company_id', $request->company);
            });

            $data['shortLeaveSetup'] = ShortLeaveSetup::where('company_id', $request->company)->first();
        }
        if ($request->filled('department')) {
            $data['short_leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department);
            });
        }
        if ($request->filled('designation')) {
            $data['short_leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('designation_id', $request->designation);
            });
        }
        if ($request->filled('employee_id')) {
            $data['short_leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_full_id', $request->employee_id);
            });
        }
        if ($request->filled('employee_group_id')) {
            $data['short_leave_applications']->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_group_id', $request->employee_group_id);
            });
        }
        if ($request->filled('from') && $request->filled('to')) {
            $data['short_leave_applications']->whereBetween('leave_day', [$request->from, $request->to]);
        }

        foreach ($data['short_leave_applications']->get() as $key => $short_leave_application) {
            $mydata['data_1'][] = $short_leave_application->employee->company->name;
            $mydata['data_2'][] = $short_leave_application->employee->name;
            $mydata['data_3'][] = $short_leave_application->employee->employee_full_id;
            $mydata['data_4'][] = $short_leave_application->employee->department->name;
            $mydata['data_5'][] = $short_leave_application->employee->designation->name;
            $mydata['data_6'][] = ucwords($short_leave_application->leave_type);
            $mydata['data_7'][] = $short_leave_application->leave_day;
            $mydata['data_8'][] = $short_leave_application->from;
            $mydata['data_9'][] = $short_leave_application->not_return == true ? "Not Return" : $short_leave_application->to;
            $mydata['data_10'][] = $short_leave_application->in_time;
            $mydata['data_11'][] = $short_leave_application->reason;
        }
        return $mydata;
    }

    // Late Adjustment
    public function lateAdjustment()
    {
        $mydata  = [];
        $data    = [];
        $request = $this->request;

        $data['employees'] = Employee::with('company', 'department', 'designation')
            ->where('status', 1)
            ->whereHas('monthly_late_deduction');

        if ($request->filled('company_id')) {
            $data['employees']->where('company_id', $request->company_id);
        }
        if ($request->filled('department_id')) {
            $data['employees']->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $data['employees']->where('designation_id', $request->designation_id);
        }
        if ($request->filled('employee_id')) {
            $data['employees']->where('employee_full_id', $request->employee_id);
        }
        if ($request->filled('employee_group_id')) {
            $data['employees']->where('employee_group_id', $request->employee_group_id);
        }
        if ($request->filled('effected_month')) {
            $data['employees']->whereHas('monthly_late_deduction', function ($q) use ($request) {
                $q->where('effected_month', $request->effected_month);
            });
        }

        foreach ($data['employees']->get() as $key => $employee) {
            $mydata['data_1'][] = $employee->employee_full_id;
            $mydata['data_2'][] = $employee->name;
            $mydata['data_3'][] = $employee->company->name;
            $mydata['data_4'][] = $employee->department->name;
            $mydata['data_5'][] = $employee->designation->name;
            $mydata['data_6'][] = $employee->monthly_late_deduction->effected_month;
            $mydata['data_7'][] = $employee->monthly_late_deduction->late_in_deduction;
            $mydata['data_8'][] = $employee->monthly_late_deduction->early_leave_deduction;
        }
        return $mydata;
    }


    // salary increment form
    public function salaryIncrementForm()
    {
        $mydata  = [];
        $data    = [];
        $request = $this->request;

        $companies_id    = Company::userCompanies()->keys();
        $departments_id  = Department::userDepartments()->keys();
        $designations_id = Designation::userDesignations()->keys();

        $previous_year = Carbon::parse($request->previous_year)->format('Y') . '-00';

        $data['employees']    = Employee::with(['department', 'designation', 'joining_salary', 'current_salary'])
                                ->with(['salaries' => function ($q) use ($request) {
                                    $q->where('approve', 1)
                                        ->where('year', '>=', ($request->previous_year ?? date('Y')));
                                }])->where('status', 1)
                                ->whereIn('company_id', $companies_id)
                                ->whereIn('department_id', $departments_id)
                                ->whereIn('designation_id', $designations_id);


        if ($request->filled('company_id') && $request->filled('department_id')) {
            $data['employees']->where('company_id', $request->company_id)
                ->where('department_id', $request->department_id);
        }
        if ($request->filled('employee_id')) {
            $data['employees']->where('employee_full_id', $request->employee_id);
        }
        if ($request->filled('employee_group_id')) {
            $data['employees']->where('employee_group_id', $request->employee_group_id);
        }

        return $data['employees'] = $data['employees']->orderByDesc('created_at')->get();

        foreach ($data['employees'] as $key => $employee) {
            $mydata['data_1'][] = $employee->employee_full_id;
            $mydata['data_2'][] = $employee->name;
            $mydata['data_3'][] = $employee->department->name;
            $mydata['data_4'][] = $employee->designation->name;
            $mydata['data_5'][] = fdate($employee->joining_date, 'm-d-Y');
            $mydata['data_6'][] = round(optional($employee->joining_salary)->total_gross);
            $mydata['data_7'][] = optional($employee->current_salary)->total_gross;
            $mydata['data_8'][] = "";
            $mydata['data_9'][] = "";
            $mydata['data_10'][] = "";
        }
        return $mydata;
    }

    // bonus create form
    public function bonusCreateForm()
    {
        $mydata  = [];
        $data    = [];
        $request = $this->request;


        if(strtolower($request->employee_type) == 'production') {
            $companyBasicSalary = BasicRateSetup::orderByDesc('effected_month')->where('effected_month', '<=', fdate($request->applicable_date, 'Y-m'))->first();
        }
        $data['employees']    = Employee::active()
                                ->employment()
                                ->searchCompany()
                                ->searchDepartment()
                                ->searchDesignation()
                                ->permissionEmployment()
                                ->searchByField('employee', 'employee_full_id')
                                ->when(strtolower($request->employee_type) == 'Regular', function($q) {
                                    $q->whereHas('salaries');
                                })
                                ->when($request->filled('employee_type'), function($q) use($request) {
                                    $q->where('employee_type', strtolower($request->employee_type));
                                })
                                ->orderBy('joining_date')
                                ->get();

        foreach ($data['employees'] ?? [] as $key => $employee) {
            $mydata['data_1'][] = $employee->employee_full_id;
            $mydata['data_2'][] = $employee->name;
            $mydata['data_3'][] = $employee->getDepartmentName();
            $mydata['data_4'][] = $employee->getDesignationName();
            $mydata['data_5'][] = fdate($employee->joining_date, 'm-d-Y');
            if(strtolower($request->employee_type) == 'production') {
                $mydata['data_6'][] = $companyBasicSalary->rate;
                $mydata['data_7'][] = "";
                $mydata['data_8'][] = "";
                $mydata['data_9'][] = "";
            } else  {
                $mydata['data_6'][] = round(optional($employee->joining_salary)->total_gross);
                $mydata['data_7'][] = optional($employee->current_salary)->total_gross;
                $mydata['data_8'][] = "";
                $mydata['data_9'][] = "";
                $mydata['data_10'][] = "";
            }
            
        }
        return $mydata;
    }
}
