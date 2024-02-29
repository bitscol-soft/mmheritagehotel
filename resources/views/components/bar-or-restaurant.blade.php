@if (setting('mother_inventory'))
    @if (active_modules()->where('name', 'Bar')->count() == 0)
        <label><input type="radio" name="bar_or_restaurant" value="0" required checked> Restaurant</label>
    @else
        <label><input type="radio" name="bar_or_restaurant" value="1" required> Bar</label>
        <label><input type="radio" name="bar_or_restaurant" value="0" required> Restaurant</label>
        {{-- <label><input type="radio" name="bar_or_restaurant" value="matrial" required> Matrial</label> --}}
    @endif
@endif
