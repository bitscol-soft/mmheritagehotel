
<table>
    <tr>
        <th colspan="14" style="text-align:center; font-size:30px; text-decoration:underline">Monthly Attendance Summary Report</th>
    </tr>
    <tr>
        <th colspan="14" style="text-align:center; font-size:24px;">{{ $company_name->name }}</th>
    </tr>
    <tr>
        <th colspan="14" style="text-align:center; font-size:20px;">{{ date('F Y', strtotime(request('month'))) }}</th>
    </tr>
    <tr>
        <th rowspan="2">SL</th>
        <th rowspan="2">Employee Id</th>
        <th rowspan="2">Name</th>
        <th rowspan="2">Department</th>
        <th rowspan="2">Designation</th>
        <th rowspan="2" title="Month Total Days">M-Days(a)</th>
        <th rowspan="2" title="Holiday">H-Days(b)</th>
        <th rowspan="2" title="Working Days">W-Days(c)</th>
        <th rowspan="2">Present(d)</th>
        <th colspan="3" style="text-align:center">Leave + Absent</th>
        <th rowspan="2">Ttl Leave (h)=(e+f+g)</th>
        <th rowspan="2">Ttl Pay Days (i)=(b+d+e )</th>
    </tr>
    <tr>
        <th>With Pay(e)</th>
        <th>With Out Pay(f)</th>
        <th>Absent(g)</th>
    </tr>

    @php 
        $totalHoliday = Module\HRM\Models\Attendance\Holiday::holidayCount(request('company_id'), (request('month') . '-01'), (request('month') . '-31')) ?? 0;
    @endphp 
    
    @foreach($employees as $key => $employee)
        @php
            $totalPayLeave = 0;
            $totalWPayLeave = 0;
            $totalWPayLeave = 0;
            $holds = $employee->holds->pluck('date')->toArray() ?? [];

            foreach ($employee->monthly_leaves as $leaves) {
                $holiday = Module\HRM\Models\Attendance\Holiday::holidayCountInLeave($employee->company_id, $leaves->from, $leaves->to) ?? 0;
                
                

                if ($leaves->leave_type->payment_mode == 'wp') {
                    $from           = \Carbon\Carbon::parse($leaves->from);
                    $to             = \Carbon\Carbon::parse($leaves->to);
                    $totalDay       = $to->diffInDays($from);
                    $totalPayLeave += ($totalDay + 1 - $holiday);
                }

                if ($leaves->leave_type->payment_mode == 'wop') {
                    $from           = \Carbon\Carbon::parse($leaves->from);
                    $to             = \Carbon\Carbon::parse($leaves->to);
                    $totalDay       = $to->diffInDays($from);
                    $totalWPayLeave += ($totalDay + 1 - $holiday);
                }
            } 

            $presentDate = [];

            foreach ($employee->attendances as $attendance){
                if(!in_array($attendance->date, $holds)) {
                    array_push($presentDate, date('Y-m-d', strtotime($attendance->date))); 
                }
            }
            
            $validDates = array_diff($presentDate, $allHolidays);
     
            $a = $total_days_of_month;
            $b = $totalHoliday;
            $c = ($total_days_of_month - $totalHoliday);
            $d = count($validDates);
            // $d = $employee->attendances->count();

            $e = $totalPayLeave;
            $f = $totalWPayLeave;
            // $g = ($total_days_of_month - $holiday) - (count($validDates) + $totalPayLeave);
            $g = ($total_days_of_month - $totalHoliday) - ($d + $totalPayLeave);
        @endphp

        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $employee->employee_full_id }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->getDepartmentName() }}</td>
            <td>{{ $employee->getDesignationName() }}</td>
            <td>{{ $a }}</td>
            <td>{{ $b }}</td>
            <td>{{ $c }}</td>
            <td>{{ $d }}</td>
            <td>{{ $e }}</td>
            <td>{{ $f }}</td>
            <td>{{ $g }}</td>
            <td>{{ $e + $f + $g }}</td>
            <td>{{ $b + $d + $e }}</td>
        </tr>
    @endforeach
</table>

