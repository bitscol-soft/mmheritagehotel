@php
    $field_column = array('data_1', 'data_2', 'data_3', 'data_4', 'data_5', 'data_6', 'data_7', 'data_8', 'data_9', 'data_10', 'data_11', 'data_12', 'data_13', 'data_14', 'data_15', 'data_16', 'data_17', 'data_18', 'data_19', 'data_20', 'data_21', 'data_22', 'data_23', 'data_24', 'data_25');
@endphp
<table>
    <thead>
        <tr>
            <th style="text-align:center; color:blue !important" colspan="{{ count($headers) }}">{{ $heading }}</th>
        </tr>
        <tr>
            @foreach ($headers as $header)
                <th width="200px">{{ $header }}</th>
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas['data_1'] as $key => $item)
            <tr>
                @for($i = 0; $i<count($headers); $i++)
                    <td style="text-align:left; padding-left:5px">{{ $datas[$field_column[$i]][$key] }}</td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>