<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Transaction;
use App\Models\PaymentType;
use App\Models\Donatur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function amount(Request $request)
    {
        $program = Program::where('is_publish', 1)->select('slug')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->slug)) {
            return view('public.donate_amount', compact('program'));
        } else {
            return view('public.not_found');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function payment(Request $request)
    {
        // $request->validate([
        //     'nominal' => 'required|numeric',
        // ]);

        $nominal = $request->nominal;
        $program = Program::where('is_publish', 1)->select('slug')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();

        if(isset($program->slug) && $nominal>=10000) {
            $payment_transfer = PaymentType::where('type', 'transfer')->orderBy('sort_number')->get();
            $payment_instant  = PaymentType::where('type', 'instant')->orderBy('sort_number')->get();
            $payment_va       = PaymentType::where('type', 'virtual_account')->orderBy('sort_number')->get();
            return view('public.payment', compact('program', 'nominal', 'payment_transfer', 'payment_instant', 'payment_va'));
        } else {
            return view('public.not_found');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function checkout(Request $request)
    {
        // $request->validate([
        //     'type'    => 'required|string',
        //     'nominal' => 'required|numeric',
        // ]);

        $nominal      = $request->nominal;
        $payment_type = $request->type;
        $program      = Program::where('is_publish', 1)->select('slug')
                        ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->slug) && $nominal>=10000) {
            $payment  = PaymentType::where('key', $payment_type)->first();
            return view('public.checkout', compact('program', 'nominal', 'payment'));
        } else {
            return view('public.not_found');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function paymentInfo(Request $request)
    {
        $request->validate([
            'type'     => 'required|string',
            'nominal'  => 'required',
            'type'     => 'required|string',
            'slug'     => 'required|string',
            'fullname' => 'required|string',
            'telp'     => 'required|numeric',
        ]);

        $nominal       = str_replace(',', '', preg_replace("/[^0-9]/", "",$request->nominal));
        $program       = Program::where('is_publish', 1)->select('slug', 'id', 'title')
                        ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        $payment       = PaymentType::where('key', $request->type)->first();
        $va_number     = '0';
        if(isset($program->slug) && $nominal>=10000 && isset($payment->name)) {
            // insert donatur
            $telp    = $this->formatTelp($request->telp);
            $donatur = Donatur::where('telp', $telp)->select('id', 'name')->first();
            if(isset($donatur->id)) {
                $donatur_id   = $donatur->id;
                $donatur_name = $donatur->name;
            } else {
                $donatur_id = Donatur::insertGetId([
                    'telp'            => $telp,
                    'name'            => trim($request->fullname), 
                    'want_to_contact' => $request->has('want_to_contact')?1:0  ]
                );
                $donatur_name = trim($request->fullname);
            }
            
            // check any transaction
            $is_trans = Transaction::where('donatur_id', $donatur_id)->where('nominal', $nominal)
                        ->whereDate('created_at', date('Y-m-d'))->where('status', '!=', 'cancel')
                        ->where('payment_type_id', $payment->id)->first();
            if(isset($is_trans->status)) {
                $transaction = $is_trans;
            } else {
                $id_increment = Transaction::select('id')->first();
                $invoice      = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);

                // Payment Gateway
                $requestBody = array(
                    'order' => array(
                        'amount'              => $nominal,
                        'invoice_number'      => $invoice,
                        'currency'            => 'IDR',
                        'callback_url'        => env('DOKU_NOTIFY_URL'),
                        'callback_url_cancel' => env('DOKU_NOTIFY_URL')
                    ),
                    'payment' => array(
                        'payment_due_date' => 1440,
                        'payment_method_types' => [$payment->payment_code]
                    ),
                    'customer' => array(
                        'id'    => $telp,
                        'name'  => $donatur_name,
                        'email' => 'bantubersamasejahtera@gmail.com',
                        'phone' => $telp
                    ),
                );

                $requestId     = Str::random(20); // Change to UUID or anything that can generate unique value
                $dateTime      = gmdate("Y-m-d H:i:s");
                $isoDateTime   = date(DATE_ISO8601, strtotime($dateTime));
                $dateTimeFinal = substr($isoDateTime, 0, 19) . "Z";

                // Generate digest
                $digestValue = base64_encode(hash('sha256', json_encode($requestBody), true));
                
                // Prepare signature component
                $componentSignature = "Client-Id:" . env('DOKU_PROD_CLIENT_ID') . "\n" .
                    "Request-Id:" . $requestId . "\n" .
                    "Request-Timestamp:" . $dateTimeFinal . "\n" .
                    "Request-Target:" . env('DOKU_PROD_TARGET') . "\n" .
                    "Digest:" . $digestValue;

                // Generate signature
                $signature = base64_encode(hash_hmac('sha256', $componentSignature, env('DOKU_PROD_SECRET_KEY'), true));

                // Execute request
                $ch = curl_init(env('DOKU_PROD_URL').env('DOKU_PROD_TARGET'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Client-Id:' . env('DOKU_PROD_CLIENT_ID'),
                    'Request-Id:' . $requestId,
                    'Request-Timestamp:' . $dateTimeFinal,
                    'Signature:' . "HMACSHA256=" . $signature,
                ));

                // Set response json
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res = json_decode($responseJson, true);
                // $res = $responseJson;
                // print_r($requestBody);
                // echo '<br><br>';
                // print_r($res);
                // yg akan digunakan jika VA 
                $link = $res['response']['payment']['url'];
                // die();




                // insert table transaction
                $transaction  = Transaction::create([
                    'program_id'      => $program->id,
                    'donatur_id'      => $donatur_id,
                    'invoice_number'  => $invoice,
                    'nominal'         => $nominal,
                    'status'          => 'draft',
                    'nominal_code'    => 137,
                    'nominal_final'   => $nominal+137,
                    'message'         => $request->has('doa')?trim($request->doa):null,
                    'payment_type_id' => $payment->id,
                    'is_show_name'    => $request->has('anonim')?1:0,
                    'user_agent'      => ''
                ]);

                $chat1 = 'Terimakasih '.ucwords(trim($request->fullname)).'.
Selangkah lagi kebaikan donasi Anda berhasil dengan menyelesaikan pembayaran berikut :
Sebesar : Rp '.str_replace(',', '.', number_format($nominal+137)).'
Nomor Invoice : '.$invoice.' 
';
                if($payment->type=='transfer') {
                    $chat2 = 'Metode : *'.$payment->name.'* 
*'.$payment->target_number.'*
a/n '.$payment->target_desc.'

';
                } elseif($payment->type=='instant') {
                    $chat2 = 'Metode : *'.$payment->name.'* 

';
                } elseif($payment->type=='virtual_account') {
                    $chat2 = 'Metode : *'.$payment->name.'* 
*0123424345897911*

';
                } else {
                    $chat2 = 'Metode : *'.$payment->name.'* 

';
                }
                
                $chat = $chat1.$chat2.'untuk program kebaikan 
*'.ucwords($program->title).'*

Terimakasih';
                $this->sentWA($telp, $chat);
            }

            // hanya dummy saja, nanti kalau payment gateway sudah jadi maka ini akan dihapus
            if($payment->type=='virtual_account'){
                $va_number = '0123424345897911';
            }
            $paid_before     = date('d F Y H:i').' WIB';
            if($payment->type=='transfer') {
                $nominal       = $nominal+137;
                $nominal_show  = str_replace(',', '.', $nominal);
                $nominal_show  = 'Rp '.substr($nominal, 0, strlen($nominal)-3).'.';
                $nominal_show2 = substr($nominal, strlen($nominal)-3, strlen($nominal));
            } else {
                $nominal_show  = 'Rp '.str_replace(',', '.', number_format($nominal));
                $nominal_show2 = '';
            }

            return view('public.payment_info', compact('nominal', 'nominal_show', 'nominal_show2', 'paid_before', 'payment', 'va_number', 'transaction', 'program'));
        } else {
            return view('public.not_found');
        }
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

    /**
     * Format phone number of the resource.
     */
    public function sentWA($telp='', $chat='')
    {
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

}
