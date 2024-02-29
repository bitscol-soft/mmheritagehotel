@php 
    $outStartTime   = fdate($shift->out_start, ' H:i:s');
@endphp

<table class="table table-bordered table-striped">
    <tr>
        <th colspan="54" style="text-align:center; font-size:30px; text-decoration:underline">Monthly Attendance Report</th>
    </tr>
    <tr>
        <th colspan="54" style="text-align:center; font-size:24px;">{{ $company_name['name'] }}</th>
    </tr>
    <tr>
        <th colspan="54" style="text-align:center; font-size:20px;">{{ date('F Y', strtotime(request('month'))) }}</th>
    </tr>
    <tr>
        <th class="bg-dark" rowspan="2" style="width: 3% !important;">SL</th>
        <th class="bg-dark" rowspan="2">Employee Id</th>
        <th class="bg-dark" rowspan="2">Employee Name</th>
        <th class="bg-dark" rowspan="2">Company</th>
        <th class="bg-dark" rowspan="2">Department</th>
        @for ($i = 1; $i <= 31; $i++)
            <th style="text-align:center"  colspan="3">{{ $i < 10 ? '0'.$i : $i }}</th>
        @endfor
    </tr>
    <tr>
        @for ($i = 1; $i <= 31; $i++)
            <td  style="text-align:center">In</td>
            <td  style="text-align:center">Out</td>
            <td  style="text-align:center">OT</td>
        @endfor
    </tr>
    @php 
        $shiftOutTime   = fdate($shift->out_start, ' H:i:s');
    @endphp

    @foreach($employees as $key => $employee_monthly_attendance)
        <tr>
            <td>{{ $key+1 }}</td>
            <td style="text-align:right">{{ $employee_monthly_attendance->employee_full_id }}</td>
            <td style="text-align:right">{{ $employee_monthly_attendance->name }}</td>
            <td style="text-align:right">{{ $employee_monthly_attendance->company->name }}</td>
            <td style="text-align:right">{{ $employee_monthly_attendance->department->name }}</td>
            @php
                $lf = 0;
                $monthDate = \Carbon\Carbon::parse($firstDate)->format('Y-m-d');
            @endphp

            @for ($i = 1; $i <= 31; $i++)
                @php
                    $isCalOt = 0;
                    $existCheckOut = null;
                    $checkHH = 0;
                    $checkWH = 0;
                    $checkH = 0;
                        $wh = 0;
                        $hh = 0;
                        $h = 0;
                        $ml = 0;
                        $ow = 0;
                        $da =  $i < 10 ? '0'.$i : $i;
                        $currentDate = (request('month').'-'.$da);

                        $existCheckOut  = null;
                        $otTime = null;

                        $leavefroms = $employee_monthly_attendance->monthly_leaves->pluck('from')->toArray();
                        $leavetos = $employee_monthly_attendance->monthly_leaves->pluck('to')->toArray();
                        $outWorks = $employee_monthly_attendance->out_workers->pluck('date')->toArray();

                        if (in_array($currentDate, $leavefroms) || in_array($lf, $leavefroms)){
                            $ml = 1;
                            if (in_array($currentDate, $leavetos) || in_array($lf, $leavetos)){
                                $lf = 0;
                            }else{
                                $lf = optional($leavefroms)[0];
                            }
                        }

                        if (in_array($currentDate,$outWorks)){
                            $ow =1;
                        }

                        if (in_array($currentDate, $weekends)){
                        $wh = 1;
                        }

                        if (in_array($currentDate, $dayToDays)){
                        $hh = 1;
                        }
                        if (in_array($currentDate, $singleDays)){
                        $h = 1;
                        }


                @endphp
                <td style="text-align:center">



                    @foreach($dayToDays as $key => $dayToDay)
                        @if ($dayToDay == $currentDate)
                            <b class="text-danger" style="font-size: 18px" title="Holiday">H</b>
                            @php
                                $checkHH += 1;
                            @endphp
                        @endif
                    @endforeach


                    @foreach($weekends as $key => $weekend)
                        @if ($weekend == $currentDate)
                            <b class="text-danger" style="font-size: 18px" title="Weekly Holiday">H</b>
                            @php
                                $checkHH += 1;
                            @endphp
                        @endif
                    @endforeach


                    @foreach($singleDays as $key => $singleDay)
                        @if ($singleDay == $currentDate)
                            <b class="text-danger" style="font-size: 18px" title="Holiday">H</b>
                            @php
                                $checkHH += 1;
                            @endphp
                        @endif
                    @endforeach

                    @if ($ml == 1)
                        @foreach($employee_monthly_attendance->monthly_leaves as $monthly_leaves)
                            @for ($m = $monthly_leaves->from ; $m <= $monthly_leaves->to; $m++)
                                @if (date('d',strtotime($m)) == ($i < 10 ? '0'.$i : $i))
                                    <b class="bolder text-danger">{{ $monthly_leaves->leave_type->name }}</b>
                                @endif
                            @endfor
                        @endforeach

                    @else
                        @php
                            $checkAbs = 0;
                        @endphp

                        @foreach($employee_monthly_attendance->attendances as $key => $attendance)

                            @if (date('d',strtotime($attendance->date)) == ($i < 10 ? '0'.$i : $i) && $ow == 0)
                            {{ $attendance->check_in_time != '' ? date('h:i',strtotime($attendance->check_in_time)) : '' }}
                            @php
                                $checkAbs += 1;
                            @endphp
                            @endif
                        @endforeach
                    @endif

                    @if ($ow == 1)
                        <span class="text-danger"><i class="fa fa-external-link"></i></span>
                    @elseif ($checkAbs == 0 && $checkHH == 0 && $ml == 0)
                        @php $isCalOt++; @endphp
                        <b class="text-danger">Abs</b>
                    @endif


                </td>

                <td>

                    @foreach($employee_monthly_attendance->attendances as $attendance)
                        @if (date('d',strtotime($attendance->date)) == ($i < 10 ? '0'.$i : $i))
                            

                            @if ($attendance->check_out_time != '')
                                @php 
                                    $existCheckOut = \Carbon\Carbon::parse($attendance->check_out_time); 
                                @endphp

                                @if (request('report_type') == 'complaince')
                                    @php 

                                        
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
                                                    // $existCheckOut = $checkOut;
                                                    $otTime = \Carbon\Carbon::parse($existCheckOut->diffInSeconds($outStartTime))->format('H.i');
                                                    if ($otTime > 2) {
                                                        $otTime = '02.00';
                                                    }
                                                } else {
                                                    $otTime = \Carbon\Carbon::parse($existCheckOut->diffInSeconds($outStartTime))->format('H.i');
                                                }
                                            } 
                                            if ($otTime > 5) {
                                                $otTime = null;
                                            }
                                        }
                                    
                                    @endphp
                                @endif

                                {{ fdate($existCheckOut, 'h:i A') }}
                            @endif
                            
                        @endif
                    @endforeach

                </td>

                
                <td>{{ $otTime }}</td>
            @endfor
        </tr>
    @endforeach
</table>
