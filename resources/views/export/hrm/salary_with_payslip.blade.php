
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
        <th class="bg" rowspan="2">SL</th>
        <th class="bg"  rowspan="2">Employee Id</th>
        <th class="bg"  rowspan="2">Name</th>
        <th class="bg"  rowspan="2">Joning Date</th>
        <th class="bg"  rowspan="2">Designation</th>
        <th class="bg"  rowspan="2">Department</th>
        <th class="bg"  rowspan="2">E-tin</th>
        <th class="bg"  rowspan="2" title="Working Days">W. Days</th>
        <th class="bg"  colspan="3" style="text-align:center">Leave</th>
        <th class="bg"  rowspan="2" title="Late Days">L. Days</th>
        <th class="bg"  rowspan="2" title="Absent Days">Abs. Days</th>
        <th class="bg"  rowspan="2" title="Present Days">P. Days</th>
        <th class="bg"  rowspan="2">Gross Bank</th>
        <th class="bg"  rowspan="2">Gross Cash</th>
        <th class="bg"  rowspan="2">Total Gross Salary</th>
        <th class="bg"  rowspan="2">Arrear Bank</th>
        <th class="bg"  rowspan="2">Arrear Cash</th>
        <th class="bg"  rowspan="2">Total Arrear</th>
        <th class="bg" style="text-align:center" colspan="6">Cash</th>
        <th class="bg" style="text-align:center" colspan="6">Bank</th>
        <th class="bg" rowspan="2">Net Payable Salary</th>
    </tr>
    <tr>
        <th class="bg" >CL</th>
        <th class="bg" >SL</th>
        <th class="bg" >SP</th>
        <th class="bg" >Absent Deduction Cash</th>
        <th class="bg" >Late Deduction Cash</th>
        <th class="bg" >Advance Deduction Cash</th>
        <th class="bg" >Loan Deduction Cash</th>
        <th class="bg" >Total Deduction Cash</th>
        <th class="bg" >Net Payable Cash</th>
        <th class="bg" >Absent Deduction Bank</th>
        <th class="bg" >Late Deduction Bank</th>
        <th class="bg" >Advance Deduction Bank</th>
        <th class="bg" >Loan Deduction Bank</th>
        <th class="bg" >Total Deduction Bank</th>
        <th class="bg" >Net Payable Bank</th>
    </tr>

    @php
        $totalBankSalary    = 0;
        $totalCashSalary    = 0;
        $totalFullSalary    = 0;
        $totalArrearbank    = 0;
        $totalArrearCash    = 0;
        $totalArrear        = 0;
        $totalAbsCash       = 0;
        $totalLateCash      = 0;
        $totalAdvncCash     = 0;
        $totalLoanCash      = 0;
        $totalDedCash       = 0;
        $totalNetPayCash    = 0;
        $totalAbsBank       = 0;
        $totalLateBank      = 0;
        $totalAdvncBank     = 0;
        $totalLoanBank      = 0;
        $totalDedBank       = 0;
        $totalNetPayBank    = 0;
        $totalNetPayAble    = 0;
    @endphp

    @foreach($salaryGeneratedInfo->salary_generated_details as $key => $salaryInfo)
        <tr>
            <td style="font-size:13">{{ $key+1 }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->employee_full_id }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->name }}</td>
            <td style="font-size:13">{{ $salaryInfo->employee->joining_date }}</td>
            <td style="font-size:13">{{ $salaryInfo->getDesignationName() }}</td>
            <td style="font-size:13">{{ $salaryInfo->getDepartmentName() }}</td>
            <td style="font-size:13">{{ optional(optional($salaryInfo->employee)->bank_information)->e_tin_number }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->working_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->cl }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->sl }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->sp }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->late_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->absent_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->pay_days }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->bank_salary }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->cash_salary }}</td>
            <td style="font-size:13" class="text-right">{{ $salaryInfo->full_salary }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->arrear)->first())->bank_value }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->arrear)->first())->cash_value }}</td>
            <td style="font-size:13" class="text-right">
                {{ (optional(optional(optional($salaryInfo->employee)->arrear)->first())->bank_value)
                    + (optional(optional(optional($salaryInfo->employee)->arrear)->first())->cash_value) }}
            </td>
            @php
                $cashSalaryPer  = (($salaryInfo->cash_salary*100)/$salaryInfo->full_salary);
                $absCashDed     = ($salaryInfo->absent_deduction*$cashSalaryPer)/100;
                $lateCashDed    = ($salaryInfo->late_deduction*$cashSalaryPer)/100;
            @endphp

            <td style="font-size:13" class="text-right">{{ number_format($absCashDed,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($lateCashDed,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->advanced)->first())->cash_value }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->loan)->first())->cash_value }}</td>
            
            @php
                $cashValue = ($absCashDed
                +$lateCashDed
                + optional(optional(optional($salaryInfo->employee)->advanced)->first())->cash_value
                + optional(optional(optional($salaryInfo->employee)->loan)->first())->cash_value)
            @endphp

            <td style="font-size:13" class="text-right">{{ number_format($cashValue,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format(($salaryInfo->cash_salary - $cashValue),0,'.','') }}</td>


            @php
                $bankSalaryPer  = (($salaryInfo->bank_salary*100)/$salaryInfo->full_salary);
                $absBankDed     = ($salaryInfo->absent_deduction*$bankSalaryPer)/100;
                $lateBankDed    = ($salaryInfo->late_deduction*$bankSalaryPer)/100;
            @endphp

            <td style="font-size:13" class="text-right">{{ number_format($absBankDed,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format($lateBankDed,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->advanced)->first())->bank_value }}</td>
            <td style="font-size:13" class="text-right">{{ optional(optional(optional($salaryInfo->employee)->loan)->first())->bank_value }}</td>
            
            @php
                $bankValue = ($absCashDed
                    + $lateCashDed
                    + optional(optional(optional($salaryInfo->employee)->advanced)->first())->bank_value
                    + optional(optional(optional($salaryInfo->employee)->loan)->first())->bank_value)
            @endphp

            <td style="font-size:13" class="text-right">{{ number_format($bankValue,0,'.','') }}</td>
            <td style="font-size:13" class="text-right">{{ number_format(($salaryInfo->bank_salary - $bankValue),0,'.','') }}</td>
            @php
                $netPayableSalary = (($salaryInfo->bank_salary - $bankValue) + ($salaryInfo->cash_salary - $cashValue))
            @endphp
            <td style="font-size: 16px; font-weight:bold">{{ (int)($netPayableSalary) }}</td>
        </tr>

        @php
            $totalBankSalary    += $salaryInfo->bank_salary;
            $totalCashSalary    += $salaryInfo->cash_salary;
            $totalFullSalary    += $salaryInfo->full_salary;
            $totalArrearbank    += optional(optional(optional($salaryInfo->employee)->arrear)->first())->bank_value;
            $totalArrearCash    += optional(optional(optional($salaryInfo->employee)->arrear)->first())->cash_value;
            $totalArrear        += (optional(optional(optional($salaryInfo->employee)->arrear)->first())->bank_value)
                                +  (optional(optional(optional($salaryInfo->employee)->arrear)->first())->cash_value);
            $totalAbsCash       += $absCashDed;
            $totalLateCash      += $lateCashDed;
            $totalAdvncCash     += optional(optional(optional($salaryInfo->employee)->advanced)->first())->cash_value;
            $totalLoanCash      += optional(optional(optional($salaryInfo->employee)->loan)->first())->cash_value;
            $totalDedCash       += $cashValue;
            $totalNetPayCash    += ($salaryInfo->cash_salary - $cashValue);
            $totalAbsBank       += $absBankDed;
            $totalLateBank      += $lateBankDed;
            $totalAdvncBank     += optional(optional(optional($salaryInfo->employee)->advanced)->first())->bank_value;
            $totalLoanBank      += optional(optional(optional($salaryInfo->employee)->loan)->first())->bank_value;
            $totalDedBank       += $bankValue;
            $totalNetPayBank    += ($salaryInfo->bank_salary - $bankValue);
            $totalNetPayAble    += $netPayableSalary;
        @endphp
    @endforeach

    <tr>
        <th colspan="14" style="font-size: 16px; font-weight:bold" class="text-left"> Total</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalBankSalary,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalCashSalary,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalFullSalary,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalArrearbank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalArrearCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalArrear,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalAbsCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalLateCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalAdvncCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalLoanCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalDedCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalNetPayCash,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalAbsBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalLateBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalAdvncBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalLoanBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalDedBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ number_format($totalNetPayBank,0,'.','') }}</th>
        <th class="text-center" style="font-size: 16px; font-weight:bold">{{ (int)($totalNetPayAble) }}</th>
    </tr>
</table>


