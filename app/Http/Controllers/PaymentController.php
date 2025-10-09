<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;
use App\Models\Donatur;
use App\Models\PaymentType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WaBlastController;
// use Faker\Provider\ar_EG\Payment;

class PaymentController extends Controller
{
    protected $paymentTypeColumn = [
        'id', 
        'key', 
        'name', 
        'is_active', 
        'sort_number', 
        'type', 
        'payment_code'
    ];

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

    // public function generate($request
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
        $serverKey = env('MID_SERVER_KEY');
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

                if($status=='settlement'){
                    // for auto WA
                    $program = Program::where('id', $transaction->program_id)->first();
                    $donatur = Donatur::where('id', $transaction->donatur_id)->first();
                    $chat    = 'Terimakasih dermawan *'.ucwords(trim($donatur->name)).'*.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Aamiin.
Atas Donasi :
*'.ucwords($program->title).'*
Sebesar : *Rp '.str_replace(',', '.', number_format($transaction->nominal_final)).'*';


                    // Kirim CAPI PIAD ke META ADS
                    $ph = !empty($transaction->phone_e164) ? hash('sha256', $transaction->phone_e164) : null;
                    // fbp/fbc hanya kirim format valid
                    $fbp = ($transaction->fbp && str_starts_with($transaction->fbp, 'fb.1.')) ? $transaction->fbp : null;
                    $fbc = ($transaction->fbc && str_starts_with($transaction->fbc, 'fb.1.')) ? $transaction->fbc : null;

                    $eventUrl = route('donate.status', [ 'inv' => $transaction->invoice_number ]);

                    $payload = [
                        'data' => [[
                            'event_name'       => 'Donate',
                            'event_time'       => (int) now()->timestamp,
                            'event_id'         => (string) $transaction->invoice_number,     // untuk dedup
                            'action_source'    => 'website',
                            'event_source_url' => $eventUrl,

                            'user_data' => array_filter([
                                'ph'                  => $ph,
                                // 'em'                  => $em,
                                'client_ip_address'   => $transaction->ip_address,
                                'client_user_agent'   => $transaction->user_agent,
                                'fbc'                 => $fbc,
                                'fbp'                 => $fbp,
                                'external_id'         => hash('sha256', (string) $transaction->donatur_id),
                            ]),

                            'custom_data' => [
                                'currency'     => 'IDR',
                                'value'        => $transaction->nominal_final,
                                'content_name' => $program->title ?? null,
                            ],
                        ]],

                        'access_token'   => env('TOKEN_FB_CAPI')
                    ];

                    try {
                        $response = Http::asJson()
                            ->acceptJson()
                            ->timeout(8)
                            ->retry(2, 200) // tahan network hiccup kecil
                            ->post('https://graph.facebook.com/v20.0/1278491429470122/events', $payload)
                            ->throw();

                        Log::info('Facebook CAPI response', [
                            'invoice' => $transaction->invoice_number,
                            'status'  => $response->status(),
                            'body'    => $response->json(),
                        ]);

                        // tandai agar tidak terkirim dua kali
                        $transaction->paid_at = now();
                        $transaction->save();
                    } catch (\Throwable $e) {
                        Log::error('Facebook CAPI error', [
                            'invoice' => $transaction->invoice_number,
                            'message' => $e->getMessage(),
                        ]);
                    }


                    // (new WaBlastController)->sentWA($donatur->telp, $chat);
                    (new WaBlastController)->sentWA($donatur->telp, $chat, 'thanks_trans', $transaction->id, $donatur->id, $program->id);
                }

                return response()->json([
                    'status' => 'success'
                ], 200);
            } else {
                echo 'transaction not found';
            }
        } else {
            echo 'signature wrong';
        }
    }

    public function select2(Request $request)
    {
        $data = PaymentType::query()->select($this->paymentTypeColumn);
        $data->where('is_active', true);

        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            $data = $data->where('name', 'like', '%' . $request->search . '%')->orWhere('key', 'like', '%' . $request->search . '%')->orWhere('type', 'like', '%' . $request->search . '%')->orWhere('payment_code', 'like', '%' . $request->search . '%');
        }

        if ($request->has('page')) {
            $data->paginate(10);
            $last_page = $data->paginate(10)->lastPage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $data->get(),
            'extra_data' => [
                'last_page' => $last_page,
            ],
        ]);
    }
}
