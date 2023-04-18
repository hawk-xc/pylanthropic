<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Transaction;
use App\Models\PaymentType;
use App\Models\Donatur;
use Illuminate\Support\Facades\DB;

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
            $donatur = Donatur::where('telp', $telp)->select('id')->first();
            if(isset($donatur->id)) {
                $donatur = $donatur->id;
            } else {
                $donatur = Donatur::insertGetId([
                    'telp'            => $telp,
                    'name'            => trim($request->fullname), 
                    'want_to_contact' => $request->has('want_to_contact')?1:0  ]
                );
            }
            
            // insert transaction
            $is_trans = Transaction::where('donatur_id', $donatur)->where('nominal', $nominal)
                        ->whereDate('created_at', date('Y-m-d'))->where('status', '!=', 'cancel')
                        ->where('payment_type_id', $payment->id)->first();
            if(isset($is_trans->status)) {
                $transaction = $is_trans;
            } else {
                $id_increment = Transaction::select('id')->first();
                $invoice      = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);
                $transaction  = Transaction::create([
                    'program_id'      => $program->id,
                    'donatur_id'      => $donatur,
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
