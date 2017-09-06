<?php
/*
    View helper functions
*/

function human_date($date)
{
    $time  = strtotime($date);
    $day   = date('j', $time);
    $month = date('n', $time) - 1;
    return $day . ' ' . __('participa.months_long.' . $month);
}

function human_month($date)
{
    $time  = strtotime($date);
    $month = date('n', $time) - 1;
    return __('participa.months_long.' . $month) . ' ' . date('Y', $time);
}

function number($number, $decimals)
{
    return number_format($number, $decimals, ',', '.');
}
