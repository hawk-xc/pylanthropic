<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\Transaction;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Callback fron DOKU
     */
    public function callbackDoku(Request $request)
    {
        // Code QR Lama
        // \Log::info($request->all());
        // \DB::table('a_log')->insert([
        //         'type' => 'qris doku outlet apps',
        //         'text' => implode(' | ', $request->all())
        //     ]);

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

}
