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
    protected $token;
    protected $host;
    public function __construct()
    {
        $this->token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU';
        $this->host  = 'https://app.ruangwa.id/api/send_message';

    }
    public function sentWA($telp='', $chat='', $type='', $trans='', $donatur='', $program='')
    {
        $telp  = $this->formatTelp($telp);
        
        // insert table chat
        \App\Models\Chat::create([
            'no_telp'        => $telp,
            'text'           => $chat,
            'token'          => $this->token,
            'vendor'         => 'RuangWA',
            'url'            => $this->host,
            'type'           => $type,
            'transaction_id' => $trans!=''?$trans:null,
            'donatur_id'     => $donatur!=''?$donatur:null,
            'program_id'     => $program!=''?$program:null
        ]);


        $curl  = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'token'   => $this->token,
            'number'  => $telp,
            'message' => $chat,
            'date'    => date('Y-m-d'),
            'time'    => date('H:i'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * Send WA for Grab Organization
     */
    public function sentWAGeneral($telp='', $chat='')
    {
        $curl  = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'token'   => $this->token,
            'number'  => $this->formatTelp($telp),
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
