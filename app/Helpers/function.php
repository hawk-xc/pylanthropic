<?php

use Illuminate\Support\Facades\Log;


/**
 * Show required state
 *
 * @param string $text
 * @param string $title
 * @return string
 */
if(!(function_exists('printRequired'))){
    function printRequired($text = '*', $title = 'Required')
    {
        return "<small class='text-danger' title='".$title."' data-toggle='tooltip' data-placement='top'>".$text."</small>";
    }
}
