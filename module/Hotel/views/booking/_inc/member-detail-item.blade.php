<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th class="text-center">SL</th>
            <th class="text-center">Name</th>
            <th class="text-center">Phone</th>
            <th class="text-center">Email</th>
            <th class="text-center">Age</th>
            <th class="text-center">Gender</th>
            <th class="text-center">Relation</th>
            <th class="text-center">NID/Passport/Registration</th>
        </tr>
    </thead>

    <tbody>
        @forelse($members as $member)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $member->name }}</td>
                <td class="text-center">{{ $member->phone }}</td>
                <td class="text-center">{{ $member->email ?? "N/A"}}</td>
                <td class="text-center">{{ $member->age }}</td>
                <td class="text-center">{{ $member->gender }}</td>
                <td class="text-center">{{ $member->relation ?? "N/A" }}</td>
                <td class="text-center">{{ $member->registration_no }}</td>
            </tr>
        @empty
            <tr>
                <th colspan="6" class="text-center">
                    <br>
                    <strong class="text-danger">No Data Found!</strong>
                    <br>
                </th>
            </tr>
        @endforelse
    </tbody>
</table>
