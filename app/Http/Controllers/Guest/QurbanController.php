<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\PaymentType;
use App\Models\Donatur;
use App\Models\Transaction;
use App\Models\TrackingVisitor;

// use App\Models\Program;

class QurbanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('public.qurban.index');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function payment(Request $request)
    {
        $id = trim($request->id);
        
        $payment_transfer = PaymentType::where('type', 'transfer')->where('is_active', 1)->orderBy('sort_number')->get();
        $payment_instant  = PaymentType::where('type', 'instant')->where('is_active', 1)->orderBy('sort_number')->get();
        $payment_va       = PaymentType::where('type', 'virtual_account')->where('is_active', 1)->orderBy('sort_number')->get();
        
        return view('public.qurban.payment', compact('id', 'payment_transfer', 'payment_instant', 'payment_va'));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function cart(Request $request)
    {
        return view('public.qurban.cart');
    }

    /**
     * Display a listing of the resource.
     */
    public function checkout(Request $request)
    {
        $id      = trim($request->id);
        $payment = trim($request->payment);
        $payment = PaymentType::where('key', $payment)->first();
        
        return view('public.qurban.checkout', compact('id', 'payment'));
    }

    /**
     * Display a listing of the resource.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'type'     => 'required|string',
            'qty'      => 'required|numeric',
            'nominal'  => 'required',
            'fullname' => 'required|string',
            'telp'     => 'required',
        ]);
        
        $nominal       = str_replace(',', '', preg_replace("/[^0-9]/", "",$request->nominal));
        $payment       = PaymentType::where('key', $request->type)->first();
        $va_number     = '0';
        $id            = $request->id;
        
        if($nominal>=1000000 && isset($payment->name)) {
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
                    'want_to_contact' => 1  
                ]);
                $donatur_name = trim($request->fullname);
            }
            
            // check any transaction
            $is_trans = Transaction::where('donatur_id', $donatur_id)->where('nominal', $nominal)
                        ->where('created_at', 'like', date('Y-m-d H').'%')->where('status', '!=', 'cancel')
                        ->where('payment_type_id', $payment->id)->first();
            if(isset($is_trans->status)) {
                $transaction    = $is_trans;
                $final_nominal  = $is_trans->nominal_final;
                $redirect_url   = $is_trans->midtrans_url;
                $token_midtrans = $is_trans->midtrans_token;
            } else {
                $id_increment = Transaction::select('id')->orderBy('id', 'DESC')->first();
                $invoice      = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);

                // get unique number for add nominal transaction
                $unique_number = $this->uniqueNumber();
                $final_nominal = $nominal+$unique_number;
                
                // Payment Gateway
                if( ($payment->type=='virtual_account' || $payment->type=='instant') && $payment->key!='qris' ){
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
                            'phone' => $telp,
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
                } elseif($payment->key=='qris') {
                    $redirect_url   = 0;
                    $token_midtrans = 0;
                } else {        // TRANSFER MANUAL
                    $redirect_url   = 0;
                    $token_midtrans = 0;
                }
                
                // insert table transaction
                $transaction  = Transaction::create([
                    'program_id'      => 1,
                    'donatur_id'      => $donatur_id,
                    'invoice_number'  => $invoice,
                    'nominal'         => $nominal,
                    'status'          => 'draft',
                    'nominal_code'    => $unique_number,
                    'nominal_final'   => $final_nominal,
                    'message'         => $request->has('doa')?trim(strip_tags($request->doa)):null,
                    'payment_type_id' => $payment->id,
                    'is_show_name'    => 1,
                    'midtrans_token'  => $token_midtrans,
                    'midtrans_url'    => $redirect_url,
                    'user_agent'      => $id,
                    'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null
                ]);
                
                // insert tracking visitor
                TrackingVisitor::create([
                    'program_id'      => 1,
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
                
                // $nominal       = $final_nominal;
                // $nominal_show  = str_replace(',', '.', $nominal);
                // $nominal_show  = 'Rp '.substr($nominal, 0, strlen($nominal)-3).'.';
                // $nominal_show2 = substr($nominal, strlen($nominal)-3, strlen($nominal));
                
                // $paid_before     = date('d F Y H:i', strtotime('24 hour')).' WIB';
                
                // return view('public.qurban.thanks', compact('id', 'nominal', 'nominal_show', 'nominal_show2', 'paid_before', 'payment', 'va_number', 'transaction', 'token_midtrans', 'redirect_url'));
                return redirect()->route( 'payment_info', $invoice);
            }
        } else {
            return view('public.not_found');
        }
        
    }
    
    /**
     * Display a listing of the resource.
     */
    public function paymentInfo(Request $request)
    {
        $invoice     = $request->inv;
        $transaction = Transaction::where('user_agent', '>=', 1)->where('invoice_number', $invoice)->first();
        if(isset($transaction->user_agent)) {
            $id            = $transaction->user_agent;
            $nominal       = $transaction->nominal_final;
            $nominal_show  = substr($nominal, 0, strlen($nominal)-3);
            $nominal_show  = str_replace(',', '.', number_format($nominal_show));
            $nominal_show  = 'Rp '.$nominal_show.'.';
            $nominal_show2 = substr($nominal, strlen($nominal)-3, strlen($nominal));
            $paid_before   = date('d F Y H:i', strtotime('24 hour')).' WIB';
            $va_number     = '0';
            $payment       = PaymentType::where('id', $transaction->payment_type_id)->first();
            
            return view('public.qurban.payment_info',
                compact('id', 'nominal', 'nominal_show', 'nominal_show2', 'paid_before', 'va_number', 'transaction', 'payment')
            );
        } else {
            return view('public.not_found');
        }
        
        
        
    }
    
    /**
     * Get unique number payment.
     */
    public function uniqueNumber($nominal='', $payment_type='')
    {
        $i = 1;
        do {
            $unique_number = rand(11, 299);
            $check         = Transaction::select('id')->where('status', 'draft')->where('nominal', $nominal)
                            ->where('payment_type_id', $payment_type)->where('nominal_code', $unique_number)->first();
            if(isset($check->id)) {
                $i = 2;
            }
        } while ($i > 1);

        return $unique_number;
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
