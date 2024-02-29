{{-- <div class="form-group">
    <div class="input-group">
        <input type="hidden" name="guest_name" id="guest_name" value="">
        <select name="hotel_guest_id" id="guest_name" class="form-control select2">
            @foreach ($guests as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <span class="input-group-addon pointer" data-toggle="modal" data-target="#add-guest-modal">
            <i class="fa fa-users"></i>
        </span>
    </div>
</div> --}}

<div class="form-group">
    <div class="input-group">
        {{-- <input type="hidden" name="guest_name" id="guest_name" value=""> --}}
        <input type="hidden" name="is_stuff" id="is_stuff" value="">
        <select type="{{ $type ?? 'text' }}" name="hotel_guest_id" id="guest_name" class="form-control select2 guest-search"
            data-placeholder="--Choose Guest--">
            <option value=""></option>
        </select>
        <span class="input-group-addon pointer" data-toggle="modal" data-target="#add-guest-modal">
        {{-- <span class="input-group-addon pointer"> --}}
            <i class="fa fa-users"></i>
        </span>
    </div>
</div>
