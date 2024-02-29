@php
$modules = Module\Permission\Models\Module::active()->get();
@endphp

<div id="sidebar" class="sidebar responsive ace-save-state main-sidebar sidebar-fixeds sidebar-scroll" data-sidebar="true"
    data-sidebar-scroll="true" data-sidebar-hover="true">
    <script type="text/javascript">
        try {
            ace.settings.loadState('sidebar')
        } catch (e) {}
    </script>


    <ul class="nav nav-list">
        <li class="{{ request()->is('hospital-dashboard') ? 'active' : '' }}">
            <a href="{{ route('home') }}">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Dashboard </span>
            </a>

            <b class="arrow"></b>
        </li>
        

        {{-- @include('partials.sidebars.__sidebar_hotel') --}}

        @foreach (session()->get('menu_modules') ?? [] as $key => $menu)
            
            @php
                $sidebar_path = getSidebarName($menu);
            @endphp

            @if ($sidebar_path != '' && view()->exists($sidebar_path))
                @include($sidebar_path)
            @endif

        @endforeach

        @include('partials.sidebars.__sidebar_mt_inventory')


    </ul>

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
            data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
