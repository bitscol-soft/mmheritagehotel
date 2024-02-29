@foreach ($roomCategories as $roomCategory)
    @if (count($roomCategory->rooms) > 0)
        <div class="row main-row">

            <p class="category-item">
                <b>{{ $roomCategory->name }} - {{ $roomCategory->price }}</b>
            </p>

            @forelse ($roomCategory->rooms as $room)
                <div class="col-md-1">
                    <div class="room-item" onclick="pickRoom(this, `{{ $roomCategory->name }}`)">
                        <input type="hidden" class="room-category-id" value="{{ $roomCategory->id }}">
                        <input type="hidden" class="room-number" value="{{ $room->room_number }}">
                        <input type="hidden" class="room-price" value="{{ $roomCategory->price }}">
                        <input type="hidden" class="room-id" value="{{ $room->id }}">
                        <p class="room-number">
                            <span style="font-size: 12px; margin-top: 5px;">Room No</span>
                            <span style="font-size: 24px">{{ $room->room_number }}</span>
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-md-12" style="text-align: left; font-weight: bold; color: rgb(230, 18, 18)">
                    No room found under this category!
                </div>
            @endforelse

        </div>
    @endif
@endforeach
