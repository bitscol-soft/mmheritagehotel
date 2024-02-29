
<table class="table table-bordered table-striped">
    
    <tr>
        <th colspan="11" style="text-align:center; font-size:30px; text-decoration:underline">Monthly Attendance Summary Report</th>
    </tr>
    <tr>
        <th colspan="11" style="text-align:center; font-size:24px;">{{ $company_name }}</th>
    </tr>
    <tr>
        <th colspan="11" style="text-align:center; font-size:20px;">{{ date('F Y', strtotime(request('month'))) }}</th>
    </tr>


    <tr>
        <td colspan="5" style="text-align: right; margin-right:20px">Present: {{ $presentEmployee }}</td>
        <td colspan="6" style="text-align: left; margin-left:20px">Absent: {{  $employees->total()-$presentEmployee }}</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center">In Time Start: {{ $shift->in_start }}</td>
        <td colspan="2" style="text-align:center">In Time End: {{ $shift->in_end }}</td>
        <td colspan="2" style="text-align:center">Out Time Start: {{ $shift->out_start }}</td>
        <td colspan="4" style="text-align:center">Out Time End: {{ $shift->out_end }}</td>
    </tr>
    <tr>
        <th style="width: 3% !important;">SL</th>
        <th>Employee Id</th>
        <th>Employee Name</th>
        <th>Department</th>
        <th>Designation</th>
        <th style="text-align:center">Check In</th>
        <th style="text-align:center">Check Out</th>
        <th style="text-align:center">Late</th>
        <th style="text-align:center">Early</th>
        <th>Official Status</th>
        <th>Work Time</th>

    </tr>

    @php
        $inTimeStart = \Carbon\Carbon::parse($shift->in_start);
        $inTimeEnd   = \Carbon\Carbon::parse($shift->in_end);
    @endphp
    @foreach($employees as $key => $employee)
        @php
            $checkIn       = \Carbon\Carbon::parse(optional($employee->today_attendances)->check_in_time);
            $checkOut      = \Carbon\Carbon::parse(optional($employee->today_attendances)->check_out_time);
            $earlydiffTime = \Carbon\Carbon::parse($checkIn->diffInSeconds($inTimeStart))->format('H:i:s');
            $latediffTime  = \Carbon\Carbon::parse($checkIn->diffInSeconds($inTimeEnd))->format('H:i:s');
            $workTime      = \Carbon\Carbon::parse($checkOut->diffInSeconds($checkIn))->format('H:i:s');


            $existCheckIn  = optional($employee->today_attendances)->check_in_time;
            $existCheckOut = optional($employee->today_attendances)->check_out_time;
        @endphp



        <tr>
            <td>{{ $key+1 }}</td>
            <td style="text-align:right">{{ $employee->employee_full_id }}</td>
            <td style="text-align:right">{{ $employee->name }}</td>
            <td style="text-align:right">{{ $employee->department->name }}</td>
            <td style="text-align:right">{{ $employee->designation->name }}</td>

            <td style="text-align:center">{{ optional($employee->today_attendances)->check_in_time ? date('Y-m-d h:i:s A',strtotime(optional($employee->today_attendances)->check_in_time)) : '' }}</td>
            <td style="text-align:center">
                {{ optional($employee->today_attendances)->check_out_time ? date('Y-m-d h:i:s A',strtotime(optional($employee->today_attendances)->check_out_time)) : '' }}
            </td>
            <td style="text-align:center">
                @if ($existCheckIn && $inTimeEnd < $checkIn)
                    {{ $latediffTime }}
                @endif
            </td>
            <td style="text-align:center">
                @if ($existCheckIn  && $inTimeStart > $checkIn)
                    {{ $earlydiffTime }}
                @endif
            </td>
            @if (optional($employee->today_attendances)->check_in_time)
                <td style="text-align:center">
                    @if (optional($employee->today_attendances)->status == 1)
                        <span title="In Office" style="color: green"><i class="fa fa-2x fa-check-square-o"></i></span>
                    @elseif (optional($employee->today_attendances)->status == 2)
                        <a target="_blank" href="{{ route('out-work.index') }}?employee_id={{ $employee->employee_full_id }}" title="Out Office" style="color: orange"><i class="fa fa-2x fa-external-link"></i></a>
                    @endif
                </td>
            @else
                <td style="text-align: center">
                    <span class="badge badge-danger">Absent</span>
                </td>
            @endif
            <td style="text-align:center">{{ $existCheckOut ? $workTime : '' }}</td>


        </tr>

    @endforeach


</table>
