<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\WaBlastController;

use App\Models\Program;
use App\Models\Donatur;
use App\Models\Transaction;
use App\Models\CheckMutation;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        if($request->api_key=='cTN6Unh0NzJmY1FrR0p3Rms2bWJrSnVrVVRVZm1ndWVoUW9pMzZkYzhnRWM0ZXZYYjBYa1VuNkdHa0ZM659c11584f804') {
            $mutasi    = $request->data_mutasi;
            $date_3ago = date('Y-m-d', strtotime(date('Y-m-d').'-3 days')).' 00:00:00';
            for($i=0; $i<count($mutasi); $i++) {
                $time_mutation = date('His', strtotime($mutasi[$i]['transaction_date']));
                if($time_mutation=='000000' || $time_mutation<1) {
                    $date_mutation_where = date('Y-m-d', strtotime($mutasi[$i]['transaction_date'])).' '.date('H:i:s');
                } else {
                    $date_mutation_where = $mutasi[$i]['transaction_date'];
                }

                $check = CheckMutation::where('amount', $mutasi[$i]['amount'])->where('mutation_date', $mutasi[$i]['transaction_date'])->select('id');
                if($check->count()<1) {
                    if(strtolower($request->module=='new_ibbiz_bri')) {
                        $bank_type    = 'bri';
                        $payment_type = 4;
                    } elseif(strtolower($request->module=='mandiri_mcm_2')) {
                        $bank_type    = 'mandiri';
                        $payment_type = 3;
                    } elseif(strtolower($request->module=='bsm')) {
                        $bank_type    = 'bsi';
                        $payment_type = 2;
                    }  elseif(strtolower($request->module=='bni_giro')) {
                        $bank_type    = 'bni';
                        $payment_type = 19;
                    }  elseif(strtolower($request->module=='bca_giro')) {
                        $bank_type    = 'bca';
                        $payment_type = 1;
                    } else {
                        $bank_type    = 'others';
                        $payment_type = null;
                    }

                    $trans_same_bank = Transaction::where('nominal_final', $mutasi[$i]['amount'])->where('payment_type_id', $payment_type)
                                        ->where('created_at', '<', $date_mutation_where)
                                        ->where('created_at', '>', $date_3ago)
                                        ->where('status', 'draft')->count();

                    if($trans_same_bank==1) {        // jika ketemu 1 data
                        $trans = Transaction::where('nominal_final', $mutasi[$i]['amount'])->where('payment_type_id', $payment_type)
                                            ->where('created_at', '<=', $date_mutation_where)
                                            ->where('created_at', '>=', $date_3ago)->where('status', 'draft')->first();
                        $trans->status     ='success';
                        $trans->updated_at = date('Y-m-d H:i:s');
                        $trans->save();
                        $id_trans         = $trans->id;

                        $in                 = new CheckMutation;
                        $in->bank_type      = $bank_type;
                        $in->apps_from      = 'MutasiBank';
                        $in->mutation_date  = $mutasi[$i]['transaction_date'];
                        $in->mutation_type  = strtolower($mutasi[$i]['type']);
                        $in->amount         = $mutasi[$i]['amount'];
                        $in->description    = $mutasi[$i]['description'];
                        $in->transaction_id = $id_trans;
                        $in->save();

                        $this->sendThanksWA($id_trans, $trans->program_id, $trans->donatur_id, $trans->nominal_final);

                    } elseif($trans_same_bank==0) { // Jika tidak ketemu, maka cari di payment lain
                        $trans = Transaction::where('nominal_final', $mutasi[$i]['amount'])->where('payment_type_id', '<>', $payment_type)
                                            ->where('created_at', '<=', $date_mutation_where)
                                            ->where('created_at', '>=', $date_3ago)->where('status', 'draft')->count();
                        if($trans==1) {
                            $trans = Transaction::where('nominal_final', $mutasi[$i]['amount'])->where('payment_type_id', '<>', $payment_type)
                                            ->where('created_at', '<', $date_mutation_where )->where('status', 'draft')->first();
                            $trans->status     ='success';
                            $trans->updated_at = date('Y-m-d H:i:s');
                            $trans->save();
                            $id_trans         = $trans->id;

                            $in                 = new CheckMutation;
                            $in->bank_type      = $bank_type;
                            $in->apps_from      = 'MutasiBank';
                            $in->mutation_date  = $mutasi[$i]['transaction_date'];
                            $in->mutation_type  = strtolower($mutasi[$i]['type']);
                            $in->amount         = $mutasi[$i]['amount'];
                            $in->description    = $mutasi[$i]['description'];
                            $in->transaction_id = $id_trans;
                            $in->save();

                            $this->sendThanksWA($id_trans, $trans->program_id, $trans->donatur_id, $trans->nominal_final);
                        } 
                        // elseif($trans==0) {       // jika ternyata tidak ketemu, maka cek di tanggal sebelumnya tapi maksimal 3 hari terakhir
                        //     $trans = Transaction::where('nominal_final', $mutasi[$i]['amount'])->where('payment_type_id', '<>', $payment_type)
                        //                 ->where('created_at', '<=', $date_mutation_where)
                        //                 ->where('created_at', '>=', $date_3ago)->where('status', 'draft')->count();

                        // } 
                        else {                    // jika ternyata lebih dari 1 data, maka cek manual saja
                            $id_trans           = null;
                            $in                 = new CheckMutation;
                            $in->bank_type      = $bank_type;
                            $in->apps_from      = 'MutasiBank';
                            $in->mutation_date  = $mutasi[$i]['transaction_date'];
                            $in->mutation_type  = strtolower($mutasi[$i]['type']);
                            $in->amount         = $mutasi[$i]['amount'];
                            $in->description    = $mutasi[$i]['description'];
                            $in->transaction_id = $id_trans;
                            $in->save();
                        }
                        
                    } else {                        // Jika ketemua lebih dari 1 data, sementara cek manual saja                          
                        $id_trans           = null;
                        $in                 = new CheckMutation;
                        $in->bank_type      = $bank_type;
                        $in->apps_from      = 'MutasiBank';
                        $in->mutation_date  = $mutasi[$i]['transaction_date'];
                        $in->mutation_type  = strtolower($mutasi[$i]['type']);
                        $in->amount         = $mutasi[$i]['amount'];
                        $in->description    = $mutasi[$i]['description'];
                        $in->transaction_id = $id_trans;
                        $in->save();
                    }
                    
                    // $in                 = new CheckMutation;
                    // $in->bank_type      = $bank_type;
                    // $in->apps_from      = 'MutasiBank';
                    // $in->mutation_date  = $mutasi[$i]['transaction_date'];
                    // $in->mutation_type  = strtolower($mutasi[$i]['type']);
                    // $in->amount         = $mutasi[$i]['amount'];
                    // $in->description    = $mutasi[$i]['description'];
                    // $in->transaction_id = $id_trans;
                    // $in->save();
                }
            }
        } else {
            $in                 = new CheckMutation;
            $in->bank_type      = 'bni';
            $in->apps_from      = 'MutasiBank';
            $in->mutation_date  = date('Y-m-d H:i:s');
            $in->mutation_type  = 'cr';
            $in->amount         = 1;
            $in->description    = 'kalau api key tidak sesuai';
            $in->transaction_id = null;
            $in->save();
        }

        return \Response::json([
            'message' => 'success',
            'status'  => 'success'
        ], 200);
    }

    /**
     * Format phone number of the resource.
     */
    public function sendThanksWA($trans_id='', $program_id='', $donatur_id='', $nominal_final='')
    {
        $program = Program::where('id', $program_id)->first();
        $donatur = Donatur::where('id', $donatur_id)->first();
        $chat    = 'Terimakasih dermawan *'.ucwords(trim($donatur->name)).'*.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Aamiin.
Atas Donasi :
*'.ucwords($program->title).'*
Sebesar : *Rp '.str_replace(',', '.', number_format($nominal_final)).'*';

            (new WaBlastController)->sentWA($donatur->telp, $chat, 'thanks_trans', $trans_id, $donatur->id, $program->id);
    }

}
