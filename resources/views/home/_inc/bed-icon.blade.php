{{-- Inline stroke icons so each bed type is distinct without a new icon font. --}}
@php
    $single = '<rect x="4" y="10" width="21.5" height="4" rx="1"/><rect x="6" y="6.6" width="9" height="3.4" rx="1.6"/><path d="M2.5 3v14.5M2.5 14h23v3.5"/>';
    $double = '<rect x="4" y="10" width="33" height="4" rx="1"/><rect x="6" y="6.6" width="13" height="3.4" rx="1.6"/><rect x="22" y="6.6" width="13" height="3.4" rx="1.6"/><path d="M2.5 3v14.5M2.5 14h35v3.5"/>';
@endphp
@if ($type === 'single')
    <svg class="mmb-bed-icon" viewBox="0 0 28 20" width="28" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $single !!}</svg>
@elseif ($type === 'double')
    <svg class="mmb-bed-icon" viewBox="0 0 40 20" width="40" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $double !!}</svg>
@elseif ($type === 'twin')
    <svg class="mmb-bed-icon" viewBox="0 0 59 20" width="59" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><g>{!! $single !!}</g><g transform="translate(31 0)">{!! $single !!}</g></svg>
@elseif ($type === 'triple')
    <svg class="mmb-bed-icon" viewBox="0 0 90 20" width="90" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><g>{!! $single !!}</g><g transform="translate(31 0)">{!! $single !!}</g><g transform="translate(62 0)">{!! $single !!}</g></svg>
@elseif ($type === 'multi')
    <svg class="mmb-bed-icon" viewBox="0 0 28 20" width="28" height="20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $single !!}</svg><span class="mmb-bed-count">&times;{{ $count }}</span>
@else
    <i class="fa fa-bed mmb-bed-icon mmb-bed-icon-generic" aria-hidden="true"></i>
@endif
