<?php
use Carbon\Carbon;

if (!function_exists('formatName')) {
    function formatName($value, $key = null) {

        $formatedValue = $key ? ($value[$key] ?? null) : $value;

        if(empty($formatedValue) || $formatedValue === '--'){
            $formatedValue = null;
        }
        else{
            $formatedValue = strlen($formatedValue) <= 2 ? strtoupper($formatedValue) : ucwords(strtolower(trim($formatedValue)));
        }

        return $formatedValue;
    }
}

if (!function_exists('formatString')) {
    function formatString($value, $key = null) {

        $formatedValue = $key ? ($value[$key] ?? null) : $value;

        if(empty($formatedValue) || $formatedValue === '--'){
            $formatedValue = null;
        }
        else{
            $formatedValue = strtoupper(trim($formatedValue));
        }

        return $formatedValue;
    }
}

if (!function_exists('formatInt')) {
    function formatInt($value, $key = null)
    {
        $formatedValue = $key ? ($value[$key] ?? 0) : $value;
        $formatedValue = !empty($formatedValue) ? (int) filter_var($formatedValue, FILTER_SANITIZE_NUMBER_INT) : 0;
        return $formatedValue;
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
