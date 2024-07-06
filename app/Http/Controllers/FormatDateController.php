<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FormatDateController extends Controller
{
    /**
     * Selisih waktu.
     */
    public function timeDonate($date='')
    {
        $awal  = date_create($date);
        $akhir = date_create();             // waktu sekarang
        $diff  = date_diff( $awal, $akhir );
        // echo $diff->y . ' tahun, ';
        // echo $diff->m . ' bulan, ';
        // echo $diff->d . ' hari, ';
        // echo $diff->h . ' jam, ';
        // echo $diff->i . ' menit, ';
        // echo $diff->s . ' detik, ';

        if($diff->y > 0) {
            $string_date = $diff->y.' tahun yang lalu';
        } elseif($diff->m > 0) {
            $string_date = $diff->m.' bulan yang lalu';
        } elseif($diff->d > 0) {
            $string_date = $diff->d.' hari yang lalu';
        } elseif($diff->h > 0) {
            $string_date = $diff->h.' jam yang lalu';
        } elseif($diff->i > 0) {
            $string_date = $diff->i.' menit yang lalu';
        } elseif($diff->s > 0) {
            $string_date = $diff->s.' detik yang lalu';
        } else {
            $string_date = '3 detik yang lalu';
        }

        return $string_date;
    }



}
