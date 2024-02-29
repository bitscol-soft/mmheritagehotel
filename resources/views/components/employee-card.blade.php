
<div style="display: flex;align-items:center">
    <span class="user-avatar">
        @if (file_exists($employee->image))
            <img class="pop rounded pointer" onclick="imageView(this)"
                src="{{ asset($employee->image) }}" style="width: 55px; height: 55px;"
             alt="{{ $employee->employee_full_id }}-{{ $employee->name }}">
        @else
            <img class="pops rounded" src="{{ asset('default-user.png') }}"
                style="width: 50px; height: 50px;"
                alt="{{ $employee->id_number . $employee->given_id_number }}-{{ $employee->name }}">
        @endif
    </span>
    <span>
        <span class="user-name">{{ $employee->name }}</span>
        <span class="user-position">ID: {{ $employee->employee_full_id }}</span>
    </span>
</div>