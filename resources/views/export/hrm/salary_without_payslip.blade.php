




<table class="table table-bordered">
    <thead>
        <tr>
            <th style="text-align:center; font-size:24px" colspan="28"><b>{{ $salaryGeneratedInfo->company->name }}</b></th>
        </tr>
        <tr>
            <th style="text-align:center; font-size:18" colspan="28"><h4>Salary Sheet For The Month of <b> {{ date('F Y', strtotime($salaryGeneratedInfo->month)) }} </b></h4></th>
        </tr>
        <tr>
            <th style="text-align:center; font-size: 16px" colspan="28">{{ optional($salaryGeneratedInfo->department)->name ?? 'All Department' }} Department{!! optional($salaryGeneratedInfo->line)->name ? (', <b>Line: </b>' . optional($salaryGeneratedInfo->line)->name) : '' !!}</th>
        </tr>
    </thead>
    <tr>
        <th class="bg" rowspan="2" style="font-size: 13px; width:200px"><b>Sl No</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Id. No</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Name</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Designation</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Joning Date</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Ttl Days</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Days Off</b></th>
        <th class="bg"  rowspan="2" title="Working Days" style="font-size: 13px;"><b>Attn. Days</b></th>
        <th class="bg"  colspan="3" style="text-align:center; font-size: 13px;"><b>Leave</b></th>
        <th class="bg"  rowspan="2" title="Absent Days" style="font-size: 13px;"><b>Abs. Days</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Pay. Days</b></th>
        <th class="bg"  rowspan="2" title="Over Time Hours" style="font-size: 13px;"><b>Ot. Hours</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Full Salary</b></th>
        <th class="bg"  colspan="7" style="text-align:center; font-size: 13px;"><b>Earning</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Gross</b></th>
        <th class="bg"  colspan="2" style="text-align:center; font-size: 13px;"><b>Deduction</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Total</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Net Payable</b></th>
        <th class="bg"  rowspan="2" style="font-size: 13px;"><b>Signature</b></th>
    </tr>
    <tr>
        <th class="bg"  style="font-size: 13px; font-weight:bold">CL</th>
        <th class="bg"  style="font-size: 13px;"><b>SL</b></th>
        <th class="bg"  style="font-size: 13px;"><b>SP</b></th>

        <th class="bg">Basic</th>
        <th class="bg"  style="font-size: 13px;"><b>House Rent</b></th>
        <th class="bg"  style="font-size: 13px;"><b>Medical</b></th>
        <th class="bg"  style="font-size: 13px;"><b>Convey</b></th>
        <th class="bg"  style="font-size: 13px;"><b>OT</b></th>
        <th class="bg"  style="font-size: 13px;"><b>Arrear</b></th>
        <th class="bg"  style="font-size: 13px;"><b>At.Bonus</b></th>

        <th class="bg"  style="font-size: 13px;"><b>Abs.Amount</b></th>
        <th class="bg"  style="font-size: 13px;"><b>Advance</b></th>
    </tr>

    @php
        $totalBankSalary = 0;
        $totalCashSalary = 0;
        $totalFullSalary = 0;
        $totalBasic = 0;
        $totalHouseRent = 0;
        $totalMedical = 0;
        $totalConvey = 0;
        $totalArrear = 0;
        $totalAtBonus = 0;
        $totalOtSalary = 0;
        $totalGross = 0;
        $totalAbsAmount = 0;
        $totalLateAmount = 0;
        $totalAdvance = 0;
        $totalIncomeTax = 0;
        $totalLoan = 0;
        $totalDeduction = 0;
        $totalNetPayable = 0;
    @endphp
    @foreach($salaryGeneratedInfo->salary_generated_details as $key => $salaryInfo)
        <tr>
            <td style="font-size:13" class="text-left">{{ $key+1 }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->employee_full_id }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->name }}</td>
            <td style="font-size:13">{{ $salaryInfo->getDesignationName() }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->joining_date }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->month_total_days,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->off_days,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->working_days,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->cl,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->sl,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->sp,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->absent_days,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->pay_days }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->over_time,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->full_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->basic_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->house_rent_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->medical_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->convey_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->over_time_salary }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->arrear_salary,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->attendance_bonus,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->gross,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->absent_deduction,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->advance_deduction,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($salaryInfo->total_deduction,0,'.','') }}</td>

            <th style="font-size: 16px; font-weight:bold" class="text-right">{{ number_format(($salaryInfo->gross - $salaryInfo->total_deduction),0,'.','') }}</th>
            <td></td>
        </tr>

        @php
            $totalFullSalary += $salaryInfo->full_salary;
            $totalBasic += $salaryInfo->basic_salary;
            $totalHouseRent += $salaryInfo->house_rent_salary;
            $totalMedical += $salaryInfo->medical_salary;
            $totalConvey += $salaryInfo->convey_salary;
            $totalArrear += $salaryInfo->arrear_salary;
            $totalAtBonus += $salaryInfo->attendance_bonus;
            $totalOtSalary += $salaryInfo->over_time_salary;
            $totalGross += $salaryInfo->gross;
            $totalAbsAmount += $salaryInfo->absent_deduction;
            $totalLateAmount += $salaryInfo->late_deduction;
            $totalAdvance += $salaryInfo->advance_deduction;
            $totalIncomeTax += $salaryInfo->income_tax_deduction;
            $totalLoan += $salaryInfo->loan_deduction;
            $totalDeduction += $salaryInfo->total_deduction;
            $totalNetPayable += ($salaryInfo->gross - $salaryInfo->total_deduction);
        @endphp
    @endforeach
    <tr>
        <th colspan="14" class="text-left" style="font-size: 16px; font-weight:bold"><b>Total</b></th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalFullSalary,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalBasic,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalHouseRent,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalMedical,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalConvey,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalOtSalary,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalArrear,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalAtBonus,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalGross,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalAbsAmount,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalAdvance,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalDeduction,0,'.','') }}</th>
        <th class="text-right" style="font-size: 16px; font-weight:bold">{{ number_format($totalNetPayable,0,'.','') }}</th>
        <th></th>
    </tr>
</table>




