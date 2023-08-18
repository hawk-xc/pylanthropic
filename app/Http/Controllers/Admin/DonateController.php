<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;
use App\Models\Donatur;
use DataTables;
use App\Http\Controllers\WaBlastController;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function statusEdit(Request $request)
    {
        // validattion
        $id_trans = $request->id_trans;
        $status   = $request->status;
        $nominal  = str_replace('.', '', $request->nominal);

        $trans = Transaction::where('id', $id_trans)->first();
        $trans->update([
            'status'        => $status,
            'nominal_final' => $nominal
        ]);

        // Send WA Terimakasih
        if($request->sendwa==1) {
            $program = Program::where('id', $trans->program_id)->first();
            $donatur = Donatur::where('id', $trans->donatur_id)->first();
            $chat    = 'Terimakasih '.ucwords(trim($donatur->name)).'.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Amin.
Atas Donasi :
*'.ucwords($program->title).'*
Sebesar : *Rp '.str_replace(',', '.', number_format($trans->nominal_final)).'*';

            // (new WaBlastController)->sentWA($donatur->telp, $chat);
            (new WaBlastController)->sentWA($donatur->telp, $chat, 'thanks_trans', $trans->id, $donatur->id, $program->id);
        }

        return array('status'=>'success', 'nominal'=>'Rp. '.number_format($nominal)); 
    }


    /**
     * Remove the specified resource from storage.
     */
    public function fuPaid(Request $request)
    {
        $id_trans = $request->id_trans;
        $status   = $request->status;

        $trans = Transaction::where('id', $id_trans)->first();

        // Send WA Terimakasih
        if(true) {
            $program = Program::where('id', $trans->program_id)->first();
            $donatur = Donatur::where('id', $trans->donatur_id)->first();
            if($status=='asli') {
                $name = ', *'.ucwords(trim($donatur->name)).'* ';
            } else {
                $name = ', ';
            }

            $chat    = 'Selangkah lagi kebaikan Anda'.$name.'akan dirasakan untuk program
*'.ucwords($program->title).'*
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($trans->nominal_final)).'*

bisa melalui
Transfer BSI - 7233152069
Transfer BRI - 041001000888302
Transfer BNI - 7060505013
Transfer Mandiri - 1370022225276
a/n Yayasan Bantu Bersama Sejahtera

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

            // (new WaBlastController)->sentWA($donatur->telp, $chat);
            (new WaBlastController)->sentWA($donatur->telp, $chat, 'fu_trans', $trans->id, $donatur->id, $program->id);
        }

        return 'success'; 
    }

    /**
     * Datatables Donatur
     */
    public function datatablesDonate()
    {
        // if ($request->ajax()) {
            $data = Transaction::select('transaction.*', 'donatur.name as name', 'donatur.telp', 'program.title', 'payment_type.name as payment_name')
                    ->join('program', 'program.id', 'transaction.program_id')
                    ->join('donatur', 'donatur.id', 'transaction.donatur_id')
                    ->join('payment_type', 'payment_type.id', 'transaction.payment_type_id');
            $data = $data->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('invoice', function($row){
                    return $row->invoice_number.'<br>'.$row->payment_name;
                })
                ->addColumn('created_at', function($row){
                    return date('Y-m-d H:i', strtotime($row->created_at));
                })
                ->addColumn('name', function($row){
                    if($row->status=='draft' || $row->status=='cancel'){
                        $param = $row->id.", '".ucwords($row->name)."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
                        $telp  = '<span class="badge badge-warning" title="Followup" style="cursor:pointer" onclick="fuPaid('.$param.')">'.$row->telp.'</span>';
                    } else {
                        $telp = $row->telp;
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('nominal_final', function($row){
                    if($row->status=='draft'){
                        $param  = $row->id.", '".$row->status."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
                        $status = '<div id="status_'.$row->id.'">Rp. '.number_format($row->nominal_final).'<br><span class="badge badge-warning modal_status" style="cursor:pointer" onclick="editStatus('.$param.')">Belum Dibayar</span></div>';
                    } elseif ($row->status=='success') {
                        $status = '<div id="status_'.$row->id.'">Rp. '.number_format($row->nominal_final).'<br><span class="badge badge-success">Sudah Dibayar</span></div>';
                    } else {
                        $param  = $row->id.", '".$row->status."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
                        // $status = '<div id="status_'.$row->id.'"><span class="badge badge-secondary">Dibatalkan</span></div>';
                        $status = '<div id="status_'.$row->id.'">Rp. '.number_format($row->nominal_final).'<br><span class="badge badge-secondary modal_status" style="cursor:pointer" onclick="editStatus('.$param.')">Dibatalkan</span></div>';
                    }
                    return $status;
                    // return 'Rp. '.number_format($row->nominal_final).'<br>'.$status;
                })
                ->addColumn('created_at', function($row){
                    $chat_history = \App\Models\Chat::select('created_at')->where('type', 'fu_trans')->where('transaction_id', $row->id);
                    if($chat_history->count()>0){
                        $chat_history = '<br><span class="badge badge-warning">('.$chat_history->count().') '.date('d-m-Y H:i', strtotime($chat_history->first()->created_at)).'</span>';
                    } else {
                        $chat_history = '';
                    }

                    // $date         = date('d-m-Y H:i', strtotime($row->created_at));
                    $date         = date('Y-m-d H:i', strtotime($row->created_at));
                    return $date.$chat_history;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'invoice', 'nominal_final', 'name', 'created_at'])
                ->make(true);
        // }
    }
}
