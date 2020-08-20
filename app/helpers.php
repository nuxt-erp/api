<?php
use Carbon\Carbon;

if (!function_exists('formatName')) {
    function formatName($value, $key = null) {

        $formattedValue = $key ? ($value[$key] ?? null) : $value;

        if(empty($formattedValue) || $formattedValue === '--'){
            $formattedValue = null;
        }
        else{
            $formattedValue = strlen($formattedValue) <= 2 ? strtoupper($formattedValue) : ucwords(strtolower(trim($formattedValue)));
        }

        return $formattedValue;
    }
}

if (!function_exists('formatString')) {
    function formatString($value, $key = null) {

        $formattedValue = $key ? ($value[$key] ?? null) : $value;

        if(empty($formattedValue) || $formattedValue === '--'){
            $formattedValue = null;
        }
        else{
            $formattedValue = strtoupper(trim($formattedValue));
        }

        return $formattedValue;
    }
}

if (!function_exists('formatInt')) {
    function formatInt($value, $key = null)
    {
        $formattedValue = $key ? ($value[$key] ?? 0) : $value;
        $formattedValue = !empty($formattedValue) ? (int) filter_var($formattedValue, FILTER_SANITIZE_NUMBER_INT) : 0;
        return $formattedValue;
    }
}
if (!function_exists('removeFromString')) {
    function removeFromString($string, $to_remove) {

        if(!is_array($to_remove))
            $to_remove = [$to_remove];

        foreach ($to_remove as $value) {
            return str_replace($value, '', $string);
        }

    }
}

if (!function_exists('left')) {
    function left($str, $length) {
        return substr($str, 0, $length);
    }
}

if (!function_exists('right')) {
    function right($str, $length) {
        return substr($str, -$length);
    }
}
