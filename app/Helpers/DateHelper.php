<?php

use Carbon\Carbon;

if (!function_exists('formatToDbDate')) {
    function formatToDbDate($date)
    {
        if (!$date) return null;

        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('formatToUserDate')) {
    function formatToUserDate($date)
    {
        if (!$date) return null;

        return Carbon::parse($date)->format('d/m/Y');
    }
}
