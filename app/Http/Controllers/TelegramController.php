<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function sendMessage($telegram_id = '', $message_text = '')
    {
        // ID GROUP Telegram "Donate - Bantusesama"    = -4062835663
        $token = '6985648237:AAH_MbbkBWUWZeUbwO-d9OKohP75siSeXsQ'; // Bantusesama Telegram Message
        $url   = "https://api.telegram.org/bot" . $token . "/sendMessage?parse_mode=markdown";
        $url   = $url . "&chat_id=" . $telegram_id . "&text=" . urlencode($message_text);

        $curl  = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            echo 'Pesan terkirim';
        }
    }

    /**
     * Format phone number of the resource.
     */
    // public function formatTelp($number='')
    // {
    //     $number = str_replace(['-', ' ', '(', ')', '+', '.'], '', $number);
    //     if(substr($number, 0, 2) == '62') {
    //         $number = '0'.substr($number, 2, 20);
    //     } elseif(substr($number, 0, 1) != '0') {
    //         $number = '0'.substr($number, 0, 20);
    //     }

    //     return $number;
    // }

}
