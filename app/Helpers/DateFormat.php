<?php
use Carbon\Carbon;



// get the formated date that you want, default format 'Y-m-d'
function fdate($value, $format = null)
{
    if ($value == '') {
        return '';
    }

    if ($format == null) {
        $format = 'Y-m-d';
    }
    return \Carbon\Carbon::parse($value)->format($format);
}


// get total days in a given month
function totalDaysInMonth($month)
{
    return Carbon::parse($month)->addMonth(1)->subDay(1)->format('d');
}
