
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
        <th class="bg-dark" rowspan="2" style="width: 10% !important">Employee Id</th>
        <th class="bg-dark" rowspan="2">Employee Name</th>
        {{-- <th class="bg-dark" rowspan="2">Company</th> --}}
        <th class="bg-dark" rowspan="2">Department</th>
        <th class="bg-dark" rowspan="2">Designation</th>
        @php
            $totalMonthDays = \Carbon\Carbon::parse(request('month'))->daysInMonth;
        @endphp
        @for ($i = 1; $i <= $totalMonthDays; $i++)
            <th style="text-align:center"  colspan="2">{{ $i < 10 ? '0'.$i : $i }}</th>
        @endfor
    </tr>
    <tr>
        @for ($i = 1; $i <= 31; $i++)
            <td  style="text-align:center; width:6% !important">In</td>
            <td  style="text-align:center; width:6% !important">Out</td>
        @endfor
    </tr>

    @foreach($employees as $key => $employee)
        <tr>
            <td class="text-left">{{ $key+1 }}</td>
            <td style="text-align:left; width:10%">{{ $employee->employee_full_id }}</td>
            <td style="text-align:left">{{ $employee->name }}</td>
            {{-- <td style="text-align:right">{{ $employee->company->name }}</td> --}}
            <td style="text-align:left">{{ $employee->department->name }}</td>
            <td style="text-align:left">{{ $employee->designation->name }}</td>

            
            @php
                $lf = 0;
                $holds = $employee->holds->pluck('date')->toArray() ?? [];
            @endphp

            @for ($i = 1; $i <= $totalMonthDays; $i++)
            @php
                $wh = 0;
                $hh = 0;
                $h = 0;
                $ml = 0;
                $ow = 0;
                $att = 0;
                $da =  $i < 10 ? '0'.$i : $i;
                $hasHoliday = 0;
                $currentDate = (request('month').'-'.$da);

                $outWorks = $employee->out_workers->pluck('date')->toArray();
                $isHold = in_array($currentDate, $holds);

                
                if(in_array($currentDate, $holds)) {

                } else if ($employee->empployee_assigned_holidays->count() > 0) {
                    if($employee->empployee_assigned_holidays->where('date', $currentDate)->first()) {
                        $h=1;
                    }


                } else {
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
                }
                

            @endphp
            
            
                <td class="text-center" style="width: 8% !important">

                    @if($isHold)
                        <b class="text-danger" title="Holiday">Hold</b>
                    @elseif ($wh > 0 || $hh > 0 || $h > 0)
                        <b class="text-danger" style="font-size: 16px" title="Holiday">H</b>
                        @php
                            $att+=1;
                            $hasHoliday = 1;
                        @endphp
                    @else
                        @foreach($employee->monthly_leaves as $monthly_leaves)
                            @if ($monthly_leaves->from <= $currentDate && $monthly_leaves->to >= $currentDate)
                                <b class="bolder text-danger">{{ $monthly_leaves->leave_type->name }}</b>
                                @php
                                    $att+=1
                                @endphp
                            @endif
                        @endforeach
                    @endif


                    @if (!$isHold && $ow > 0)
                        @php
                            $att += 1;
                        @endphp
                        <span class="text-danger"><i
                                class="fa fa-external-link"></i></span>
                    @elseif(!$isHold)

                        @foreach($employee->attendances as $attendance)
                            @if (date('Y-m-d', strtotime($attendance->date)) == $currentDate)
                                @php
                                    $att+=1;
                                    $workTime = 0;

                                    if ($attendance->check_out_time){
                                        $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                        $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);

                                        $workTime = $checkOut->diffInHours($checkIn);
                                    }

                                @endphp

                                {{ $attendance->check_in_time != '' ? date('h:i A',strtotime($attendance->check_in_time)) : '' }}

                                @if($workTime <= 3)
                                    <i class="fa fa-volume-up text-danger"></i>
                                @endif

                            @endif
                        @endforeach
                    @endif

                    @if (!$isHold && $att == 0)
                        <span class="bolder text-danger" style="font-size: 18px">Abs</span>
                    @endif

                </td>

                <td>

                    @if(!$isHold)
                        @foreach($employee->attendances as $attendance)
                            @if (date('d',strtotime($attendance->date)) == ($i < 10 ? '0'.$i : $i))
                                {{ $attendance->check_out_time != '' ? date('h:i A',strtotime($attendance->check_out_time)) : '' }}
                            @endif
                        @endforeach
                    @endif

                </td>


            @endfor
        </tr>

    @endforeach


</table>
