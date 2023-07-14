<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\Transaction;
use App\Models\Program;
use App\Models\Donatur;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\WaBlastController;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function generate($requestBody="")
    {
        $requestId     = Str::random(20); // Change to UUID or anything that can generate unique value
        $dateTime      = gmdate("Y-m-d H:i:s");
        $isoDateTime   = date(DATE_ISO8601, strtotime($dateTime));
        $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";

        // Generate digest
        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

        // Generate Digest
        $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
        echo "Digest: " . $digestValue;
        echo "<br><br>";

        // Prepare signature component
        $componentSignature = "Client-Id:" . env('DOKU_PROD_CLIENT_ID') . "\n" .
            "Request-Id:" . $requestId . "\n" .
            "Request-Timestamp:" . $dateTimeFinal . "\n" .
            "Request-Target:/doku-virtual-account/v2/payment-code\n" .
            // "Request-Target:" . env('DOKU_PROD_TARGET') . "\n" .
            "Digest:" . $digestValue;

        echo "Component Signature: \n" . $componentSignature;
        echo "<br><br>";


        // Generate signature
        $signature = base64_encode(hash_hmac('sha256', $componentSignature, env('DOKU_PROD_SECRET_KEY'), true));

        echo "Signature: " . $signature;
        echo "<br><br>";


        $headerSignature =  "Client-Id:" . env('DOKU_PROD_CLIENT_ID') ."\n". 
                    "Request-Id:" . $requestId . "\n".
                    "Request-Timestamp:" . $dateTimeFinal ."\n".
                    "Signature:" . "HMACSHA256=" . $signature;


        // Execute request
        // $ch = curl_init(env('DOKU_PROD_URL').env('DOKU_PROD_TARGET'));
        $ch = curl_init('https://api-sandbox.doku.com/bca-virtual-account/v2/payment-code');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $headerSignature
        ));

        // Set response json
        $responseJson = curl_exec($ch);
        $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $res          = json_decode($responseJson, true);
        print_r($res);
        // yg akan digunakan jika VA 
        // $link         = $res['response']['payment']['url'];
        // return $link;
        return $res;
    }

    // public function generate($requestBody="")
    // {
    //     $requestId     = Str::random(20); // Change to UUID or anything that can generate unique value
    //     $dateTime      = gmdate("Y-m-d H:i:s");
    //     $isoDateTime   = date(DATE_ISO8601, strtotime($dateTime));
    //     $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";

    //     // Generate digest
    //     $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));

    //     // Prepare signature component
    //     $componentSignature = "Client-Id:" . env('DOKU_PROD_CLIENT_ID') . "\n" .
    //         "Request-Id:" . $requestId . "\n" .
    //         "Request-Timestamp:" . $dateTimeFinal . "\n" .
    //         "Request-Target:" . env('DOKU_PROD_TARGET') . "\n" .
    //         "Digest:" . $digestValue;

    //     // Generate signature
    //     $signature = base64_encode(hash_hmac('sha256', $componentSignature, env('DOKU_PROD_SECRET_KEY'), true));

    //     // Execute request
    //     $ch = curl_init(env('DOKU_PROD_URL').env('DOKU_PROD_TARGET'));
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //         'Content-Type: application/json',
    //         'Client-Id:' . env('DOKU_PROD_CLIENT_ID'),
    //         'Request-Id:' . $requestId,
    //         'Request-Timestamp:' . $dateTimeFinal,
    //         'Signature:' . "HMACSHA256=" . $signature,
    //     ));

    //     // Set response json
    //     $responseJson = curl_exec($ch);
    //     $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);
    //     $res          = json_decode($responseJson, true);
    //     // yg akan digunakan jika VA 
    //     $link         = $res['response']['payment']['url'];
    //     return $link;
    // }

    /**
     * Callback fron DOKU
     */
    public function callbackDoku(Request $request)
    {
        $status       = $request->status;
        $order_number = $request->reference_id;
        $pay_amount   = $request->total;

        if($status == 'berhasil'){
            $transaction = Transaction::where('invoice_number', $order_number)->where('nominal_final', $pay_amount)->first();

            if(!empty($transaction)){
                $transaction->status  = 'success';
                $transaction->paid_at = date('Y-m-d H:i:s');
                $transaction->save();

                return response()->json([
                    'status' => 'success'
                ], 200);
            }
        }
    }

    /**
     * Callback fron MIDTRANS
     */
    public function callbackMidtrans(Request $request)
    {
        $serverKey = $serverKey = env('MID_SERVER_KEY');
        $hashed    = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        if($hashed == $request->signature_key) {
            $status       = $request->transaction_status;
            $order_number = $request->order_id;
            $pay_amount   = explode('.', $request->gross_amount);
            $pay_amount   = $pay_amount[0];

            $transaction = Transaction::where('invoice_number', $order_number)->where('nominal_final', $pay_amount)->first();
            
            if(!empty($transaction)){
                if($status=='settlement'){
                    $transaction->status  = 'success';
                } elseif($status=='pending') {              // Transaction is captured by Midtrans but pending payment from customer

                } elseif($status=='capture') {              // Transaction is captured by Midtrans

                } else {                                    // deny, cancel, expired, refund, partial_refund
                    $transaction->status  = 'cancel';
                }
                
                $transaction->paid_at = date('Y-m-d H:i:s');
                $transaction->save();

                // for auto WA
                $program = Program::where('id', $transaction->program_id)->first();
                $donatur = Donatur::where('id', $transaction->donatur_id)->first();
                $chat = 'Terimakasih '.ucwords(trim($donatur->name)).'.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Amin.
Atas Donasi :
*'.ucwords($program->title).'*
Sebesar : Rp '.str_replace(',', '.', number_format($transaction->nominal_final));

                (new WaBlastController)->sentWA($donatur->telp, $chat);

                return response()->json([
                    'status' => 'success'
                ], 200);
            } else {
                echo 'transaction not found';
            }
        } else {
            echo 'signature wrong';
        }
        // Log::debug($request);
        // $request = json_decode($request, true);

        // $status       = $request['transaction_status'];
        // $order_number = $request['order_id'];
        // $pay_amount   = explode('.', $request['gross_amount']);
        
        // // $status       = $request->transaction_status;
        // // $order_number = $request->order_id;
        // // $pay_amount   = explode('.', $request->gross_amount);
        
        // $pay_amount   = $pay_amount[0];

        // if($status == 'berhasil'){
        //     $transaction = Transaction::where('invoice_number', $order_number)->where('nominal_final', $pay_amount)->first();

            
        // }
    }

}
