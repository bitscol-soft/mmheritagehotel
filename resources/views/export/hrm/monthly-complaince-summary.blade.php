
<table>
    <tr>
        <th colspan="14" style="text-align:center; font-size:30px; text-decoration:underline">Monthly Attendance Summary Report</th>
    </tr>
    <tr>
        <th colspan="14" style="text-align:center; font-size:24px;">{{ $edited_company_name ?? $company_name->name }}</th>
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
        <th rowspan="2">Total OT</th>
        <th rowspan="2">Total OT Days</th>
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
        $totalMonthDays = \Carbon\Carbon::parse(request('month'))->daysInMonth; 
        $shiftOutTime   = fdate($shift->out_start, ' H:i:s');
    @endphp

    @foreach($employees as $key => $employee)
        @php
            $totalOts = 0;
            $totalOTDays = 0;

            $totalPayLeave = 0;
            $totalWPayLeave = 0;

            foreach ($employee->leaves as $leaves){
                if ($leaves->leave_type->payment_mode == 'wp'){
                    $from = \Carbon\Carbon::parse($leaves->from);
                    $to = \Carbon\Carbon::parse($leaves->to);
                    $totalDay = $to->diffInDays($from);
                    $totalPayLeave += ($totalDay+1);
                }

                if ($leaves->leave_type->payment_mode == 'wop'){
                    $from = \Carbon\Carbon::parse($leaves->from);
                    $to = \Carbon\Carbon::parse($leaves->to);
                    $totalDay = $to->diffInDays($from);
                    $totalWPayLeave += ($totalDay+1);
                }
            }

            $a = $total_days_of_month;
            $b = $holiday;
            $c = ($total_days_of_month - $holiday);
            $d = $employee->attendances_count;
            $e = $totalPayLeave;
            $f = $totalWPayLeave;
            $g = ($total_days_of_month - $holiday) - $employee->attendances_count;



            // calculate ot

            for ($i = 1; $i <= $totalMonthDays; $i++) {
                $existCheckOut  = null;
                $otTime         = null;

                foreach($employee->attendances as $attendance) {
                    if (date('d',strtotime($attendance->date)) == ($i < 10 ? '0'.$i : $i)) {
                        

                        if ($attendance->check_out_time != '') {
                            $existCheckOut = \Carbon\Carbon::parse($attendance->check_out_time); 
                                    
                            if ($existCheckOut) {

                                $outStartTime   = \Carbon\Carbon::parse(fdate($existCheckOut, 'Y-m-d') . $shiftOutTime);
                                if (fdate($outStartTime, 'H') <= fdate($existCheckOut, 'H')) {
                
                                    $out = \Carbon\Carbon::parse($existCheckOut);
                                    $ot  = \Carbon\Carbon::parse($out->diffInSeconds($outStartTime))->format('H.i');


                                    if (request('random') == 1 && $ot >= '02.00') {
                                        $hour = \Carbon\Carbon::parse($outStartTime)->format('H');
                                        if (request('random') == 1 && fdate($existCheckOut, 'i') > $shift->random_min) {
                                            $otMin = rand(1, $shift->random_min);
                                        } else {
                                            $otMin = fdate($existCheckOut, 'i');
                                        }
                                        $existCheckOut = \Carbon\Carbon::parse(fdate($existCheckOut, 'Y-m-d') . ($hour + 2) . ':' . $otMin .  fdate($existCheckOut, ':s'));
                                        $otTime = \Carbon\Carbon::parse($existCheckOut->diffInSeconds($outStartTime))->format('H.i');
                                        if ($otTime > 2) {
                                            $otTime = 2;
                                        }
                                    } else {
                                        $otTime = \Carbon\Carbon::parse($existCheckOut->diffInSeconds($outStartTime))->format('H.i');
                                    }
                                } 
                                if ($otTime > 5) {
                                    $otTime = 0;
                                }
                            }
                        }
                        
                    }
                }

                if ((double)$otTime > 0) {
                    $totalOts     += (double)$otTime;
                    $totalOTDays++;
                }   
            }
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
            <td>{{ $totalOts }}</td>
            <td>{{ $totalOTDays }}</td>
            <td>{{ $e }}</td>
            <td>{{ $f }}</td>
            <td>{{ $g }}</td>
            <td>{{ $e + $f + $g }}</td>
            <td>{{ $b + $d + $e }}</td>
        </tr>
    @endforeach
</table>

