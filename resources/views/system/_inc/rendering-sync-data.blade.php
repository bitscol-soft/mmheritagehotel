
@forelse ($sync_data ?? [] as $item)
    @php
        $total_item = $item->count();
        $total_completed = $item->where('is_completed', 1)->count();
        $total_employee = $item->sum('total_employees');
        
        $progress = ceil(100 / $total_item) * $total_completed;
        
        $item = $item->first();
        if ($progress >= 100) {
            $progress = 100;
            $message = $item->date . ' is processing, total employee: <b>' . ($total_employee ?? 0) . '</b>';
            // $message = $item->date . ' is processing done.';
        } else {
            $message = $item->date . ' is processing...';
        }
        
    @endphp
    <li class="item-{{ $item->is_completed ? 'green' : 'orange' }} clearfix">
        @if ($item->is_completed)
            <div class="accordion">
                <label class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                        href="#collapse{{ $loop->iteration }}" aria-expanded="false">
                        <i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down"
                            data-icon-show="ace-icon fa fa-angle-right"></i>
                        {!! $message !!}
                    </a>
                </label>


                <div class="pull-right easy-pie-chart percentage" data-size="45" data-color="#ECCB71"
                    data-percent="{{ $progress }}">
                    <span class="percent">{{ $progress }}</span>%
                </div>


                <div class="panel-collapse collapse" id="collapse{{ $loop->iteration }}" aria-expanded="true">
                    <div class="panel-body">
                       Currently No employees to show
                    </div>
                </div>
            </div>
        @else
    <li class="item-{{ $item->is_completed ? 'green' : 'orange' }} clearfix">
        <label>{!! $message !!}</label>
        <div class="pull-right easy-pie-chart percentage" data-size="45" data-color="#ECCB71"
            data-percent="{{ $progress }}">
            <span class="percent">{{ $progress }}</span>%
        </div>
    </li>
@endif


</li>
@empty
@endforelse
