
<!DOCTYPE html>
<html>
<head>

    <title>{{ $heading }}</title>
    <style>
        table, td, th {
            border: 1px solid black;
            font-size: 10px;
        }
        table {
            border-top:none;
            border-left:none;
            border-right:none;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            padding-left: 2px !important;
        }
        /*th {*/
        /*    width: 100px;*/
        /*}*/
    </style>

</head>
<body>
@php
    $field_column = array('data_1', 'data_2', 'data_3', 'data_4', 'data_5', 'data_6', 'data_7', 'data_8', 'data_9', 'data_10', 'data_11', 'data_12', 'data_13', 'data_14', 'data_15', 'data_16', 'data_17', 'data_18', 'data_19', 'data_20', 'data_21', 'data_22', 'data_23', 'data_24', 'data_25', 'data_26', 'data_27', 'data_28', 'data_29', 'data_30', 'data_31', 'data_32', 'data_33', 'data_34', 'data_35', 'data_36', 'data_37', 'data_38', 'data_39', 'data_40','data_41', 'data_42', 'data_43', 'data_44', 'data_45', 'data_46', 'data_47', 'data_48', 'data_49', 'data_50', 'data_51', 'data_52', 'data_53', 'data_54', 'data_55', 'data_56', 'data_57', 'data_58', 'data_59','data_60', 'data_61', 'data_62', 'data_63', 'data_64', 'data_65', 'data_66', 'data_67', 'data_68', 'data_69', 'data_70');
@endphp
<div>

    <table>
        <thead>
        <tr>
            <th style="text-align:center; font-size:30px; border:none !important" colspan="{{ count($headers)+1 }}">{{ $company_name }}</th>
        </tr>
        <tr>
            <th style="text-align:center; font-size:20px; border: none !important;" colspan="{{ count($headers)+1 }}">{!! $heading !!}</th>
        </tr>
        <tr>
            <th><b>SL</b></th>
            @foreach ($headers as $header)
                <th><b>{{ $header }}</b></th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if (count($datas) > 0)
            @foreach($datas['data_1'] as $key => $item)
                <tr>
                    <td>{{  $key + 1 }}</td>
                    @for($i = 0; $i<count($headers); $i++)
                        <td>{!! $datas[$field_column[$i]][$key] !!}</td>
                    @endfor
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</div>
</body>
</html>
