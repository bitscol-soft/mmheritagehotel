{{-- <div class="row" style="margin-left: 15px">
    <hr>
    <strong style="font-size: 16px"> Available Rooms: </strong>
    <hr>
</div> --}}

@foreach ($roomCategories as $roomCategory)
    <div class="row main-row">

        <p class="category-item">
            <b>{{ $roomCategory->name }} - {{ $roomCategory->price }}</b>
        </p>


        @forelse ($roomCategory->rooms as $room)
            <div class="col-md-1">
                <div class="room-item" onclick="pickRoom(this)">
                    <input type="hidden" class="room-category-id" value="{{ $roomCategory->id }}">
                    <input type="hidden" class="room-number" value="{{ $room->room_number }}">
                    <input type="hidden" class="room-price" value="{{ $roomCategory->price }}">
                    <input type="hidden" class="room-id" value="{{ $room->id }}">
                    <p class="room-number">
                        <span style="font-size: 12px; margin-top: 5px;">Room No</span>
                        <span style="font-size: 24px">{{ $room->room_number }}</span>
                    </p>

                    <div class="guest-popup" hidden>
                        <div class="guest-popup-body">
                            <div class="gs-header">
                                <div class="gs-title">
                                    <span>
                                        <i class="fa fa-exclamation-circle"></i>
                                    </span> Room Information
                                </div>
                            </div>
                            <div class="gs-name">
                                Room Category : {{ $roomCategory->name }}
                            </div>
                            <div class="gs-name">
                                Room Price : {{ $roomCategory->price }}
                            </div>
                            <div class="gs-des">
                                Description: {{ $roomCategory->description }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12" style="text-align: left; font-weight: bold; color: rgb(230, 18, 18)">
                No room found under this category!
            </div>
        @endforelse

    </div>
@endforeach
