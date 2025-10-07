<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Transaction;
use App\Models\PaymentType;
use App\Models\Donatur;
use App\Models\TrackingVisitor;
use App\Models\SpamLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\PaymentController;
use Carbon\Carbon;

use Illuminate\Support\Facades\Http;

class DonateController extends Controller
{
    protected $rwa_token;

    public function __construct()
    {
        $this->rwa_token = \App\Models\TokenConfig::first()->token ?? env('RWA_TOKEN');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function amount(Request $request)
    {
        $program = Program::where('is_publish', 1)->select('slug', 'id', 'count_amount_page')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->slug)) {
            // update count_amount_page
            Program::where('id', $program->id)->update([
                'count_amount_page' => $program->count_amount_page+1,
                'updated_at'        => date('Y-m-d H:i:s')
            ]);

            // insert tracking visitor
            TrackingVisitor::create([
                'program_id'      => $program->id,
                'visitor_code'    => 1,
                'page_view'       => 'amount',
                'nominal'         => 0,
                'payment_type_id' => null,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null,
                'utm_source'      => (isset($request->a)) ? strip_tags($request->a) : null,
                'utm_medium'      => (isset($request->as)) ? strip_tags($request->as) : null,
                'utm_campaign'    => null,
                'utm_content'     => (isset($request->k)) ? strip_tags($request->k) : null
            ]);

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
        // 1) Ambil nominal baik dari input/body, query, atau route param
        $rawNominal = $request->input('nominal', $request->route('nominal'));

        // 2) Sanitasi: buang semua karakter non-digit -> jadikan integer
        $sanitizedNominal = (int) preg_replace('/[^\d]/', '', (string) $rawNominal);

        // 3) Merge kembali ke request biar validator pakai nilai bersih
        $request->merge(['nominal' => $sanitizedNominal]);

        // 4) Validasi (pakai integer agar pasti bilangan bulat)
        $validated = $request->validate([
            'nominal' => ['required','integer','min:20000','max:500000000'],
        ], [
            'nominal.min' => 'Nominal minimal 20 ribu.',
            'nominal.max' => 'Nominal maksimal 500 juta.',
        ]);

        $nominal = (int) $validated['nominal'];
        $program = Program::where('is_publish', 1)->select('slug', 'id')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();

        if(isset($program->slug) && $nominal>=10000) {
            $payment_transfer = PaymentType::where('type', 'transfer')->where('is_active', 1)->orderBy('sort_number')->get();
            $payment_instant  = PaymentType::where('type', 'instant')->where('is_active', 1)->orderBy('sort_number')->get();
            $payment_va       = PaymentType::where('type', 'virtual_account')->where('is_active', 1)->orderBy('sort_number')->get();

            // insert tracking visitor
            TrackingVisitor::create([
                'program_id'      => $program->id,
                'visitor_code'    => 1,
                'page_view'       => 'payment_type',
                'nominal'         => $nominal,
                'payment_type_id' => null,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null,
                'utm_source'      => (isset($request->a)) ? strip_tags($request->a) : null,
                'utm_medium'      => (isset($request->as)) ? strip_tags($request->as) : null,
                'utm_campaign'    => null,
                'utm_content'     => (isset($request->k)) ? strip_tags($request->k) : null
            ]);

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
        $program      = Program::where('is_publish', 1)->select('slug', 'id', 'count_pra_checkout')
                        ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->slug) && $nominal>=10000) {
            // update count pra checkout
            Program::where('id', $program->id)->update([
                'count_pra_checkout' => $program->count_pra_checkout+1,
                'updated_at'         => date('Y-m-d H:i:s')
            ]);

            $payment  = PaymentType::where('key', $payment_type)->first();

            // insert tracking visitor
            TrackingVisitor::create([
                'program_id'      => $program->id,
                'visitor_code'    => 1,
                'page_view'       => 'form',
                'nominal'         => $nominal,
                'payment_type_id' => $payment->id,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null,
                'utm_source'      => (isset($request->a)) ? strip_tags($request->a) : null,
                'utm_medium'      => (isset($request->as)) ? strip_tags($request->as) : null,
                'utm_campaign'    => null,
                'utm_content'     => (isset($request->k)) ? strip_tags($request->k) : null
            ]);

            $telp = '';
            $name = '';
            if(isset($request->telp)) {
                $telp = urldecode($request->telp);
            }

            if(isset($request->name)) {
                $name = urldecode($request->name);
            }

            return view('public.checkout', compact('program', 'nominal', 'payment', 'telp', 'name'));
        } else {
            return view('public.not_found');
        }
    }

    public function paymentStatus(Request $request)
    {
        // check any transaction
        $is_trans = Transaction::where('invoice_number', $request->inv)->first();
        if(isset($is_trans->status)) {
            $nominal        = $is_trans->nominal_final;
            $nominal_show   =  number_format(substr($nominal, 0, strlen($nominal)-3), 0, ',', '.');
            $nominal_show   = 'Rp '.$nominal_show.'.';
            $nominal_show2  = substr($nominal, strlen($nominal)-3, strlen($nominal));
            $token_midtrans = $is_trans->midtrans_token;
            $redirect_url   = $is_trans->midtrans_url;
            $va_number      = 0;
            $paid_before    = 0;
            $payment        = PaymentType::where('id', $is_trans->payment_type_id)->first();
            $transaction    = $is_trans;
            $program        = Program::where('id', $is_trans->program_id)->first();
            $link           = $is_trans->link;

            return view('public.payment_info', compact('nominal', 'nominal_show', 'nominal_show2', 'paid_before', 'payment', 'va_number', 'transaction', 'program', 'token_midtrans', 'redirect_url', 'link'));
        } else {
            return view('public.not_found');
        }
    }

    public function paymentInfo(Request $request)
    {
        $request->validate([
            'type'     => 'required|string',
            'nominal'  => 'required',
            'slug'     => 'required|string',
            'fullname' => 'required|string',
            'telp'     => 'required|numeric',
        ]);

        $captcha = $request->input('g-recaptcha-response');
        $captchav3 = $request->input('recaptcha_v3_token');

        $verify = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.v2_secret'),
            'response' => $captcha,
            'remoteip' => $request->ip(),
        ]);

        $verifyV3 = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.v3_secret'),
            'response' => $captchav3,
            'remoteip' => $request->ip(),
        ]);

        $result = $verify->json();
        $resultV3 = $verifyV3->json();

        if (empty($result['success']) || !$result['success']) {
            return back()->withErrors(['captcha' => 'reCAPTCHA gagal, silakan coba lagi.']);
        }

        if (empty($resultV3['success']) || $resultV3['score'] < 0.5) {
            return back()->withErrors(['captcha' => 'reCAPTCHA score rendah, aktivitas mencurigakan terdeteksi.']);
        }

        if (!empty($request->input('payment_method_check'))) {
            abort(403, 'not fulled');
        }

        $nominal       = str_replace(',', '', preg_replace("/[^0-9]/", "",$request->nominal));
        $program       = Program::where('is_publish', 1)->select('slug', 'id', 'title')
                        ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        $payment       = PaymentType::where('key', $request->type)->first();
        $va_number     = '0';
        $link          = null;

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

            // Filter Spammer
            $deviceId  = $request->attributes->get('bb_did') ?? $request->cookie('bb_did');
            $uaRaw     = $request->header('User-Agent') ?? null;
            $uaCore    = \App\Helpers\UserAgentHelper::parseCore($uaRaw);
            $ipAddress = $request->ip() ?? null;
            $sessionId = $request->session()->getId();
            $fingerprintId = $request->fingerprint;

            $check = checkSuspect($nominal, $deviceId, $uaCore, $ipAddress, $sessionId, $fingerprintId);

            if ($check['is_suspect'] == 1) {
                return redirect()->route('donate.status', ['inv' => $check['invoice_number']])
                    ->with('warning', 'Anda sudah membuat donasi berulang kali namun belum dibayar.');
            }
            
            // check any transaction
            $is_trans = Transaction::where('donatur_id', $donatur_id)
                        ->where('nominal', $nominal)
                        ->where('program_id', $program->id)
                        ->where('payment_type_id', $payment->id)
                        ->where('status', '!=', 'cancel')
                        // ->where('created_at', 'like', date('Y-m-d H').'%')
                        ->whereBetween('created_at', [now()->subMinutes(3), now()->addMinute()])
                        ->latest('id')->first();

            if(isset($is_trans->status)) {
                return redirect()->route('donate.status', ['inv' => $is_trans->invoice_number]);
            }

            $id_increment = Transaction::select('id')->orderBy('id', 'DESC')->first();
            $invoice      = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);

            // get unique number for add nominal transaction
            $unique_number = $this->uniqueNumber();
            $final_nominal = $nominal+$unique_number;

            // Payment Gateway
            if($payment->key=='qris') {
                $requestBody = array(
                    'payment_type' => 'qris',    // gopay / shopeepay
                    'transaction_details' => array(
                        'order_id'     => $invoice,
                        'gross_amount' => $final_nominal,
                    ),
                    'item_details' => array(
                        'id'       => $invoice,
                        'price'    => $final_nominal,
                        'quantity' => 1,
                        'name'     => $invoice,
                    ),
                    'customer_details' => array(
                        'first_name' => $donatur_name,
                        'phone'      => $telp,
                    ),
                    'qris' => array(
                        'acquirer' => 'gopay'
                    )
                );

                $ch = curl_init(env('GOPAY_URL'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept:application/json',
                    'Content-Type:application/json',
                    'Authorization:Basic '.base64_encode(env('MID_SERVER_KEY')),
                ));
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res          = json_decode($responseJson, true);

                if(isset($res['actions'][0]['url'])) {
                    $redirect_url   = $res['actions'][0]['url'];
                    $token_midtrans = 0;
                } else {
                    // QRIS Manual
                    $redirect_url   = asset('public/images/payment/QRIS.png');
                    $token_midtrans = 0;
                }

            } elseif($payment->key=='gopay') {
                $requestBody = array(
                    'payment_type' => 'gopay',    // gopay / shopeepay
                    'transaction_details' => array(
                        'order_id'     => $invoice,
                        'gross_amount' => $final_nominal,
                    ),
                    'item_details' => array(
                        'id'       => $invoice,
                        'price'    => $final_nominal,
                        'quantity' => 1,
                        'name'     => $invoice,
                    ),
                    'customer_details' => array(
                        'first_name' => $donatur_name,
                        'phone'      => $telp,
                    ),
                    'gopay' => array(
                        'enable_callback' => false
                        // 'callback_url'    => route('')
                    )
                );

                $ch = curl_init(env('GOPAY_URL'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept:application/json',
                    'Content-Type:application/json',
                    'Authorization:Basic '.base64_encode(env('MID_SERVER_KEY')),
                ));
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res          = json_decode($responseJson, true);

                if(isset($res['actions'][0]['url']) && isset($res['actions'][1]['url'])) {
                    $redirect_url   = $res['actions'][0]['url']; // deeplink
                    $token_midtrans = 0;
                    $link           = $res['actions'][1]['url']; // QRIS

                    foreach ($res['actions'] as $action) {
                        if ($action['name'] === 'deeplink-redirect') {
                            $redirect_url = $action['url'];
                        }

                        if ($action['name'] === 'generate-qr-code') {
                            $link   = $action['url'];
                        }
                    }
                } else {
                    // QRIS Manual
                    $redirect_url   = asset('public/images/payment/QRIS.png');
                    $token_midtrans = 0;
                    $link           = asset('public/images/payment/QRIS.png');
                }

            }  elseif($payment->key=='shopeepay') {
                $requestBody = array(
                    'payment_type' => 'shopeepay',    // gopay / shopeepay
                    'transaction_details' => array(
                        'order_id'     => $invoice,
                        'gross_amount' => $final_nominal,
                    ),
                    'item_details' => array(
                        'id'       => $invoice,
                        'price'    => $final_nominal,
                        'quantity' => 1,
                        'name'     => $invoice,
                    ),
                    'customer_details' => array(
                        'first_name' => $donatur_name,
                        'phone'      => $telp,
                    ),
                    'shopeepay' => array(
                        'callback_url'    => route('program.index', $program->slug)
                    )
                );

                $ch = curl_init(env('GOPAY_URL'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept:application/json',
                    'Content-Type:application/json',
                    'Authorization:Basic '.base64_encode(env('MID_SERVER_KEY')),
                ));
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res          = json_decode($responseJson, true);

                if(isset($res['actions'][0]['url'])) {
                    $redirect_url   = $res['actions'][0]['url'];
                    $token_midtrans = 0;
                } else {
                    // QRIS Manual
                    
                    $redirect_url   = asset('public/images/payment/QRIS.png');
                    $token_midtrans = 0;
                }

            } elseif( ($payment->type=='virtual_account' || $payment->type=='instant') && $payment->key!='qris' ){ // belum aktif
                $requestBody = array(
                    'transaction_details' => array(
                        'order_id'     => $invoice,
                        'gross_amount' => $final_nominal,
                    ),
                    'credit_card' => array(
                        'secure' => true
                    ),
                    'customer_details' => array(
                        'first_name' => $donatur_name,
                        'phone'      => $telp,
                    )
                );

                $ch = curl_init(env('MID_URL'));  // https://app.midtrans.com/snap/v1/transactions
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept:application/json',
                    'Content-Type:application/json',
                    'Authorization:Basic '.base64_encode(env('MID_SERVER_KEY')),
                ));
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res          = json_decode($responseJson, true);

                if(isset($res['redirect_url'])) {
                    $redirect_url   = $res['redirect_url'];
                    $token_midtrans = $res['token'];
                } else {
                    return view('public.not_found');    // ada problem payment gateway
                }
            } else {        // TRANSFER MANUAL
                $redirect_url   = 0;
                $token_midtrans = 0;
            }

            // insert table transaction
            $transaction  = Transaction::create([
                'program_id'      => $program->id,
                'donatur_id'      => $donatur_id,
                'invoice_number'  => $invoice,
                'nominal'         => $nominal,
                'status'          => 'draft',
                'nominal_code'    => $unique_number,
                'nominal_final'   => $final_nominal,
                'message'         => $request->has('doa')?trim(strip_tags($request->doa)):null,
                'payment_type_id' => $payment->id,
                'is_show_name'    => $request->boolean('anonim')?0:1,
                'midtrans_token'  => $token_midtrans,
                'midtrans_url'    => $redirect_url,
                'link'            => $link,
                'user_agent'      => $uaRaw,
                'ua_core'         => $uaCore,
                'device_id'       => $deviceId,
                'ip_address'      => $ipAddress,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null
            ]);

            // CAPI - Convertion API for META Ads
            // Http::post('https://graph.facebook.com/v18.0/1278491429470122/events', [
            //     'data' => [
            //         [
            //             'event_name' => 'Donate',
            //             'event_time' => time(),
            //             'event_id'   => $invoice,
            //             'user_data'  => [
            //                   'ph'   => $telp,
            //             ],
            //             'custom_data'   => [
            //                 'currency'      => 'IDR',
            //                 'value'         => $nominal,
            //             ],
            //             'action_source' => 'website',
            //         ],
            //     ],
            //     'access_token' => env('FACEBOOK_CAPI_TOKEN'),
            // ]);
            // \Log::info('Facebook CAPI response:', [$response->json()]);


            // insert tracking visitor
            TrackingVisitor::create([
                'program_id'      => $program->id,
                'visitor_code'    => 1,
                'page_view'       => 'invoice',
                'nominal'         => $nominal,
                'payment_type_id' => $payment->id,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null,
                'utm_source'      => (isset($request->a)) ? strip_tags($request->a) : null,
                'utm_medium'      => (isset($request->as)) ? strip_tags($request->as) : null,
                'utm_campaign'    => null,
                'utm_content'     => (isset($request->k)) ? strip_tags($request->k) : null
            ]);

            return redirect()->route('donate.status', ['inv' => $invoice]);
        } else {
            return view('public.not_found');
        }
    }

    public function paymentInfoMidtransCoreAPI(Request $request)
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
                $transaction   = $is_trans;
                $final_nominal = $is_trans->nominal_final;
            } else {
                $id_increment = Transaction::select('id')->first();
                $invoice      = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);

                // get unique number for add nominal transaction
                $unique_number = $this->uniqueNumber();
                $final_nominal = $nominal+$unique_number;

                // Payment Gateway
                if($payment->type=='virtual_account'){
                    if($payment->key=='va_bca'){
                        $requestBody = array(
                            'payment_type' => 'bank_transfer',
                            'bank_transfer' => array(
                                'bank' => 'bca'
                            ),
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            ),
                            'customer_details' => array(
                                'first_name' => $donatur_name,
                                'phone' => $telp,
                            )
                        );
                    } elseif($payment->key=='va_bni') {
                        $requestBody = array(
                            'payment_type' => 'bank_transfer',
                            'bank_transfer' => array(
                                'bank' => 'bni'
                            ),
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            ),
                            'customer_details' => array(
                                'first_name' => $donatur_name,
                                'phone' => $telp,
                            )
                        );
                    } elseif($payment->key=='va_bri') {
                        $requestBody = array(
                            'payment_type' => 'bank_transfer',
                            'bank_transfer' => array(
                                'bank' => 'bri'
                            ),
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            ),
                            'customer_details' => array(
                                'first_name' => $donatur_name,
                                'phone' => $telp,
                            )
                        );
                    } elseif($payment->key=='va_permata') {
                        $requestBody = array(
                            'payment_type' => 'permata',
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            ),
                            'customer_details' => array(
                                'first_name' => $donatur_name,
                                'phone' => $telp,
                            )
                        );
                    } elseif($payment->key=='va_mandiri') {
                        $requestBody = array(
                            'payment_type' => 'echannel',
                            'echannel' => array(
                                'bill_info1' => 'Payment',
                                'bill_info2' => 'Online purchase'
                            ),
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            ),
                            'customer_details' => array(
                                'first_name' => $donatur_name,
                                'phone' => $telp,
                            )
                        );
                    } elseif($payment->key=='gopay') {
                        $requestBody = array(
                            'payment_type' => 'gopay',
                            'transaction_details' => array(
                                'order_id' => $invoice,
                                'gross_amount' => $final_nominal,
                            )
                        );
                    }
                    // Danamon, CIMB, BSI belum
                }
                

                $ch = curl_init("https://api.sandbox.midtrans.com/v2/charge");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept:application/json',
                    'Content-Type:application/json',
                    'Authorization:Basic '.base64_encode('SB-Mid-server-smxoK3uKAUYyJ3lx15qe-ktj:'),
                ));
                $responseJson = curl_exec($ch);
                $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $res          = json_decode($responseJson, true);

                if(isset($res['va_numbers'][0]['va_number'])) {         // VA for BCA, BNI, BRI
                    $va_number = $res['va_numbers'][0]['va_number'];
                } elseif(isset($res['permata_va_number'])) {            // VA for Permata
                    $va_number = $res['permata_va_number'];
                } elseif(isset($res['bill_key'])) {                     // VA for Mandiri
                    $va_number = $res['bill_key'];
                } elseif(isset($res['actions'])) {                     // VA for Mandiri
                    
                } else {
                    // print_r($res);
                    // echo $res['va_numbers'][0]['va_number'];
                    return view('public.not_found');    // ada problem payment gateway
                }
            }


            // insert table transaction
            $ip        = $request->ip() ?? '-';
            $userAgent = $request->header('User-Agent') ?? '-';

            $transaction  = Transaction::create([
                'program_id'      => $program->id,
                'donatur_id'      => $donatur_id,
                'invoice_number'  => $invoice,
                'nominal'         => $nominal,
                'status'          => 'draft',
                'nominal_code'    => $unique_number,
                'nominal_final'   => $final_nominal,
                'message'         => $request->has('doa')?trim($request->doa):null,
                'payment_type_id' => $payment->id,
                'is_show_name'    => $request->has('anonim')?1:0,
                'user_agent'      => $ip.' | '.$userAgent,  // ✅ gabungan IP + User Agent
            ]);

            // if($payment->type=='transfer') {             // sementara ditambahkan 3 digit semua
                $nominal       = $final_nominal;
                $nominal_show  = str_replace(',', '.', $nominal);
                $nominal_show  = 'Rp '.substr($nominal, 0, strlen($nominal)-3).'.';
                $nominal_show2 = substr($nominal, strlen($nominal)-3, strlen($nominal));
            // } else {
            //     $nominal_show  = 'Rp '.str_replace(',', '.', number_format($nominal));
            //     $nominal_show2 = '';
            // }

            $paid_before     = date('d F Y H:i').' WIB';

            return view('public.payment_info', compact('nominal', 'nominal_show', 'nominal_show2', 'paid_before', 'payment', 'va_number', 'transaction', 'program'));

        } else {
            return view('public.not_found');
        }
    }

    public function paymentStatusCheck(Request $request)
    {
        $inv         = $request->inv;
        $transaction = Transaction::select('status')->where('invoice_number', trim($inv))->first();
        if(isset($transaction->status)) {
            if($transaction->status=='draft') {
                return 'Belum Diterima';
            } elseif($transaction->status=='success') {
                return 'Sudah Dibayar';
            } else {
                return 'Dibatalkan';
            }
        } else {
            return 'no';
        }
    }

    /**
     * Send Notification to Telegram Group "Donate - Bantubersama"
     */
    public function sendNotifTelegram($invoice='')
    {
        $data = Transaction::select('transaction.*', 'donatur.name as name', 'donatur.telp', 'program.title', 'payment_type.name as payment_name')
                    ->join('program', 'program.id', 'transaction.program_id')
                    ->join('donatur', 'donatur.id', 'transaction.donatur_id')
                    ->join('payment_type', 'payment_type.id', 'transaction.payment_type_id')
                    ->where('invoice_number', $invoice)->first();
        if(isset($data->telp)) {
            $chat1     = "Donasi baru *Rp.".str_replace(',', '.', number_format($data->nominal_final))."* (*".$data->payment_name."*)
a/n *".$data->name." - ".$data->telp."* 
untuk program *".$data->title."*";

            $count_all    = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->count();
            $sum_all      = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->sum('nominal_final');
            $count_paid   = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')->count();
            $sum_paid     = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')->sum('nominal_final');
            $count_unpaid = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->where('status', '<>', 'success')->count();
            $sum_unpaid   = Transaction::select('id')->where('created_at', 'like', date('Y-m-d').'%')->where('status', '<>', 'success')->sum('nominal_final');

            $chat2     = "

*Today Report ".date('d-m-Y')."*
Total Donasi : ".number_format($count_all)." - Rp.".str_replace(',', '.', number_format($sum_all))."
Donasi Dibayar : ".number_format($count_paid)." - Rp.".str_replace(',', '.', number_format($sum_paid))."
Donasi Belum Dibayar : ".number_format($count_unpaid)." - Rp.".str_replace(',', '.', number_format($sum_unpaid));

            // ID GROUP Telegram "Donate - Bantubersama"    = -4062835663
            (new \App\Http\Controllers\TelegramController)->sendMessage('-4062835663', $chat1.$chat2);
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
        // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
        // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
        // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'token'   => $this->rwa_token,
            'number'  => $telp,
            'message' => $chat,
            'date'    => date('Y-m-d'),
            'time'    => date('H:i'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }

    /**
     * Get unique number payment.
     */
    public function uniqueNumber($nominal='', $payment_type='')
    {
        $i = 1;
        do {
            $unique_number = rand(11, 499);
            $check         = Transaction::select('id')->where('status', 'draft')->where('nominal', $nominal)
                            ->where('payment_type_id', $payment_type)->where('nominal_code', $unique_number)->first();
            if(isset($check->id)) {
                $i = 2;
            }
        } while ($i > 1);

        return $unique_number;
    }

}
