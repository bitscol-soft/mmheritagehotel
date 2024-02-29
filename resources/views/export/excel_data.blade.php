

@if (request('model') != 'Salary Increment Form')
    @php
        $field_column = array('data_1', 'data_2', 'data_3', 'data_4', 'data_5', 'data_6', 'data_7', 'data_8', 'data_9', 'data_10', 'data_11', 'data_12', 'data_13', 'data_14', 'data_15', 'data_16', 'data_17', 'data_18', 'data_19', 'data_20', 'data_21', 'data_22', 'data_23', 'data_24', 'data_25', 'data_26', 'data_27', 'data_28', 'data_29', 'data_30', 'data_31', 'data_32', 'data_33', 'data_34', 'data_35', 'data_36', 'data_37', 'data_38', 'data_39', 'data_40','data_41', 'data_42', 'data_43', 'data_44', 'data_45', 'data_46', 'data_47', 'data_48', 'data_49', 'data_50', 'data_51', 'data_52', 'data_53', 'data_54', 'data_55', 'data_56', 'data_57', 'data_58', 'data_59','data_60', 'data_61', 'data_62', 'data_63', 'data_64', 'data_65', 'data_66', 'data_67', 'data_68', 'data_69', 'data_70');
    @endphp

    <table>
        <thead>
            <tr>
                <th style="text-align:center; font-size:30px" colspan="{{ count($headers) }}">{{ $company_name }}</th>
            </tr>
            <tr>
                <th style="text-align:center; font-size:20px" colspan="{{ count($headers) }}">{!! $heading !!}</th>
            </tr>
            <tr>
                @foreach ($headers as $header)
                    <th><b>{{ $header }}</b></th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if (count($datas) > 0)
                @foreach($datas['data_1'] as $key => $item)
                    <tr>
                        @for($i = 0; $i<count($headers); $i++)
                            <td style="padding-left:5px">{{ $datas[$field_column[$i]][$key] }}</td>
                        @endfor
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@else 

    @php
        $end_date = \Carbon\Carbon::parse(request('previous_year'));

        $dissabled = "";
        $row_backgrond = "";

        $previous_year   = (int)(request('previous_year'));
        $year_to = (int)(substr(request('from_date'), 0, 4));

        $month_from  = substr(request('from_date'), 5);

        $currentYear = fdate(request('from_date'), 'Y');

        $repeatColspan = ($currentYear - $previous_year) * 3;

        $eligible = Module\HRM\Models\Salary\EligibleSetting::where('company_id', request('company_id'))->first();
    @endphp

    <table>
        <thead>
            <tr>
                <th style="text-align:center; font-size:30px" colspan="{{ 13 + $repeatColspan }}">{{ $company_name }}</th>
            </tr>
            <tr>
                <th style="text-align:center; font-size:20px" colspan="{{ 13 + $repeatColspan }}">{!! $heading !!}</th>
            </tr>

            <tr>
                <th rowspan="2" style="width: 3% !important; vartical-align: middle">SL</th>
                <th rowspan="2" style="vertical-align: middle;">Employee Id</th>
                <th rowspan="2" style="vertical-align: middle;">Employee Name</th>
                <th rowspan="2" style="vertical-align: middle;">Department</th>
                <th rowspan="2" style="vertical-align: middle;">Designation</th>
                <th rowspan="2" style="vertical-align: middle;">Join Date</th>
                <th rowspan="2" style="vertical-align: middle; text-align: right" title="Joining Salary">Join SL</th>

                @for($i = $previous_year; $i<=$currentYear; $i++)
                    <th colspan="3" style="text-align:center">{{ $i }}</th>
                @endfor
                <th rowspan="2" style="vertical-align: middle;" class="text-center">Increment</th>
                <th rowspan="2" style="vertical-align: middle;" class="text-center">Precent</th>
                <th rowspan="2" style="vertical-align: middle;" class="text-center">Total</th>
            </tr>
            <tr>
                @for($i = $previous_year; $i<=$currentYear; $i++)
                    <th title="Percent" style="width:60px; text-align: center">Per</th>
                    <th title="Increment" style="width:60px; text-align: right">Inc</th>
                    <th style="width:60px; text-align: right">Salary</th>
                @endfor
            </tr>
        </thead>


        <tbody>
            @foreach($datas as $key => $employee)
                @if ($end_date->diffInDays(\Carbon\Carbon::parse($employee->joining_date)) <  (($eligible->duration*30)))
                    @php
                        $dissabled = "readonly";
                        $row_backgrond = 'danger';
                    @endphp
                @else
                    @php
                        $dissabled = "";
                        $row_backgrond = '';
                    @endphp
                @endif

                <tr>
                    <td>{{ $key + 1 }} </td>
                    <td>{{ $employee->employee_full_id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->department->name }}</td>
                    <td>{{ $employee->designation->name }}</td>
                    <td>{{ fdate($employee->joining_date, 'm-d-Y') }}</td>
                    <td>{{ round(optional($employee->joining_salary)->total_gross) }}</td>


                    @for($i = $previous_year; $i<=$currentYear; $i++)
                        @php $currentSalary = 0 @endphp
                        @if(count($employee->salaries->where('year', $i)) > 0)
                            <td>
                                @foreach ($employee->salaries as $in => $increment)
                                    @if($in > 0), @endif {{ $increment->percent }}
                                @endforeach
                            </td>
                            <td style="text-align: center">
                                @foreach ($employee->salaries as $inc => $increment)
                                @if($inc > 0), @endif{{ round($increment->increment) }}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($employee->salaries as $incr => $increment)
                                @if($incr > 0), @endif{{ round($increment->total_gross) }}
                                @endforeach
                            </td>
                        @else
                            <td></td>
                            <td></td>
                            <td>
                                @if ($i == $currentYear)
                                    {{ optional($employee->current_salary)->total_gross }}
                                @endif
                            </td>
                        @endif

                    @endfor

                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
            @endforeach

            <tr class="text-center" style="font-size:15px !important; font-weight:900px !important">
                <td colspan="{{ (($currentYear - $previous_year + 1) * 3) + 10 }}" class="text-right">Total </td>
               
                <td class="total_increment text-right"></td>
                <td class="total_percent"></td>
                <td class="grand_total"></td>
              
            </tr>

        </tbody>

    </table>
@endif
