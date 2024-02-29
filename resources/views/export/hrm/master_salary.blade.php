
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align:center; font-size:24px" colspan="28"><b>{{ $salaryGeneratedInfo->company->name }}</b></th>
        </tr>
        <tr>
            <th style="text-align:center; font-size:18" colspan="28"><h4>Salary Sheet For The Month of <b> {{ date('F Y', strtotime($salaryGeneratedInfo->month)) }} </b></h4></th>
        </tr>
        <tr>
            <th style="text-align:center; font-size: 16px" colspan="28">{{ optional($salaryGeneratedInfo->department)->name ?? 'All Department' }} Department</th>
        </tr>
    </thead>
    <tr>
        <th style="font-size: 13px;" rowspan="2">SL</th>
        <th style="font-size: 13px;" rowspan="2">Employee Id</th>
        <th style="font-size: 13px;" rowspan="2">Name</th>
        <th style="font-size: 13px;" rowspan="2">Designation</th>
        <th style="font-size: 13px;" rowspan="2">E-tin</th>
        <th style="font-size: 13px;" rowspan="2">Grade</th>
        <th style="font-size: 13px;" rowspan="2">Joning Date</th>
        <th style="font-size: 13px;" rowspan="2">Ttl Days</th>
        <th style="font-size: 13px;" rowspan="2">Off Days</th>
        <th style="font-size: 13px;" rowspan="2" title="Working Days">W. Days</th>
        <th style="font-size: 13px;" rowspan="2" title="Present Days">P. Days</th>
        <th style="font-size: 13px;" colspan="3">Leave</th>
        <th style="font-size: 13px;" rowspan="2" title="Absent Days">Abs. Days</th>
        <th style="font-size: 13px;" rowspan="2" title="Late Days">L. Days</th>
        <th style="font-size: 13px;" rowspan="2" title="Over Time Hours">Ot. Hours</th>
        <th style="font-size: 13px;" rowspan="2">Pay days</th>
        <th style="font-size: 13px;" rowspan="2">Bank</th>
        <th style="font-size: 13px;" rowspan="2">Cash</th>
        <th style="font-size: 13px;" rowspan="2">Full Salary</th>
        <th style="font-size: 13px;" colspan="7">Earning</th>
        <th style="font-size: 13px;" rowspan="2">Gross</th>
        <th style="font-size: 13px;" colspan="5">Deduction</th>
        <th style="font-size: 13px;" rowspan="2">Total</th>
        <th style="font-size: 13px;" rowspan="2">Net Payable</th>
        <th style="font-size: 13px;" rowspan="2">Signature</th>
    </tr>
    <tr>
        <th style="font-size: 13px;" >CL</th>
        <th style="font-size: 13px;" >SL</th>
        <th style="font-size: 13px;" >SP</th>

        <th style="font-size: 13px;" >Basic</th>
        <th style="font-size: 13px;" >House Rent</th>
        <th style="font-size: 13px;" >Medical</th>
        <th style="font-size: 13px;" >Convey</th>
        <th style="font-size: 13px;" >Arrear</th>
        <th style="font-size: 13px;" >At.Bonus</th>
        <th style="font-size: 13px;" title="Over Time Salary">Ot.Salary</th>

        <th style="font-size: 13px;" >Abs.Amount</th>
        <th style="font-size: 13px;" >Late Amount</th>
        <th style="font-size: 13px;" >Advance</th>
        <th style="font-size: 13px;" >Income tax</th>
        <th style="font-size: 13px;" >Loan</th>
    </tr>

    @php
        $totalBankSalary    = 0;
        $totalCashSalary    = 0;
        $totalFullSalary    = 0;
        $totalBasic         = 0;
        $totalHouseRent     = 0;
        $totalMedical       = 0;
        $totalConvey        = 0;
        $totalArrear        = 0;
        $totalAtBonus       = 0;
        $totalOtSalary      = 0;
        $totalGross         = 0;
        $totalAbsAmount     = 0;
        $totalLateAmount    = 0;
        $totalAdvance       = 0;
        $totalIncomeTax     = 0;
        $totalLoan          = 0;
        $totalDeduction     = 0;
        $totalNetPayable    = 0;
    @endphp

    @foreach($salaryGeneratedInfo->salary_generated_details as $key => $salaryInfo)
        <tr style="height:100px !important">
            <td style="font-size:13">{{ $key+1 }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->employee_full_id }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->name }}</td>
            <td style="font-size:13">{{ $salaryInfo->getDesignationName() }}</td>
            <td style="font-size:13">{{ optional(optional($salaryInfo->employee)->bank_information)->e_tin_number }}</td>
            <td style="font-size:13">{{ optional(optional($salaryInfo->employee)->grade)->grade_name }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->joining_date }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->month_total_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->off_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->working_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->present_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->cl }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->sl }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->sp }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->absent_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->late_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->over_time }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->pay_days }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->bank_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->cash_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->full_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->basic_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->house_rent_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->medical_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->convey_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->arrear_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->attendance_bonus }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->over_time_salary }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->gross }}</td>

            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->absent_deduction }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->late_deduction }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->advance_deduction }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->income_tax_deduction }}</td>
            <td style="font-size:13" class="text-right">{{ (int)$salaryInfo->loan_deduction }}</td>
            <td style="font-size:13" class="text-right">{{ (int)($salaryInfo->total_deduction) }}</td>

            <th style="font-size: 16px; font-weight:bold" class="text-right">
                {{ (int)($salaryInfo->gross - $salaryInfo->total_deduction) }}
            </th>
            <td ></td>
        </tr>

        @php
            $totalBankSalary    += $salaryInfo->bank_salary;
            $totalCashSalary    += $salaryInfo->cash_salary;
            $totalFullSalary    += $salaryInfo->full_salary;
            $totalBasic         += $salaryInfo->basic_salary;
            $totalHouseRent     += $salaryInfo->house_rent_salary;
            $totalMedical       += $salaryInfo->medical_salary;
            $totalConvey        += $salaryInfo->convey_salary;
            $totalArrear        += $salaryInfo->arrear_salary;
            $totalAtBonus       += $salaryInfo->attendance_bonus;
            $totalOtSalary      += $salaryInfo->over_time_salary;
            $totalGross         += $salaryInfo->gross;
            $totalAbsAmount     += $salaryInfo->absent_deduction;
            $totalLateAmount    += $salaryInfo->late_deduction;
            $totalAdvance       += $salaryInfo->advance_deduction;
            $totalIncomeTax     += $salaryInfo->income_tax_deduction;
            $totalLoan          += $salaryInfo->loan_deduction;
            $totalDeduction     += $salaryInfo->total_deduction;
            $totalNetPayable    += ($salaryInfo->gross - $salaryInfo->total_deduction);
        @endphp
    @endforeach

    <tr style="font-size: 19px !important; font-weight:normal !important">
        <th colspan="18" style="font-size: 16px; font-weight:bold" class="text-left"> Total </th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalBankSalary }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalCashSalary }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalFullSalary }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalBasic }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalHouseRent }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalMedical }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalConvey }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalArrear }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalAtBonus }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalOtSalary }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalGross }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalAbsAmount }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalLateAmount }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalAdvance }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalIncomeTax }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalLoan }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalDeduction }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)$totalNetPayable }}</th>
        <th class="text-center"></th>
    </tr>
</table>
                




