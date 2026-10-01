<x-mm.panel class="mm-filter-panel">
    <form action="" class="mm-setup-filter">
        <div class="input-group">
            <span class="input-group-addon">Title</span>
            <input type="text" name="title" class="form-control" value="{{ request('title') }}">
        </div>
        <button type="submit" class="mm-button"><i class="fa fa-search"></i> Search</button>
        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset"><i class="fa fa-refresh"></i> Reset</a>
    </form>
</x-mm.panel>
