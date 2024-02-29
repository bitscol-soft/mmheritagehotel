<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Company</th>
            <th>Unit</th>
            <th>Rate</th>
            <th>Opening Balance</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->item_unit->name }}</td>
            <td>{{ $item->rate }}</td>
            <td>{{ $item->opening_balance }}</td>
        </tr>
    @endforeach
    </tbody>
</table>