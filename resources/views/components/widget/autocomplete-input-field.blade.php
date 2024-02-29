<div class="form-group">
    <label class="control-label">
        {{ $title }}: 
        @if ($isrequired ?? '')
            <sup class="text-danger">*</sup>
        @endif
    </label>

    <input type="hidden" name="hotel_room_id" id="hotel_room_id">

    <input type="text" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" {{ $value }} class="form-control" @if ($isrequired ?? '') required @endif>

</div>