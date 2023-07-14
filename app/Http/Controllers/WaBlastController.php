<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class WaBlastController extends Controller
{
    public function sentWA($telp='', $chat='')
    {
        $telp = $this->formatTelp($telp);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'token'   => 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3',
            'number'  => $telp,
            'message' => $chat,
            'date'    => date('Y-m-d'),
            'time'    => date('H:i'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * Format phone number of the resource.
     */
    public function formatTelp($number='')
    {
        $number = str_replace(['-', ' ', '(', ')', '+', '.'], '', $number);
        if(substr($number, 0, 2) == '62') {
            $number = '0'.substr($number, 2, 20);
        } elseif(substr($number, 0, 1) != '0') {
            $number = '0'.substr($number, 0, 20);
        }

        return $number;
    }

}
