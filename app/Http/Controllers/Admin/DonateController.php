<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;
use App\Models\Donatur;
use App\Models\TrackingVisitor;
use DataTables;
use App\Http\Controllers\WaBlastController;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $last_donate = Transaction::select('created_at')->orderBy('created_at', 'desc')->first()->created_at;
        return view('admin.transaction.index', compact('last_donate'));
    }

    /**
     * Display a listing of the resource.
     */
    public function donateCheckAlarm(Request $request)
    {
        // $last_donate = date('Y-m-d H:i:s', strtotime($request->last_donate));
        $check_alarm = date('Y-m-d H:i:s', strtotime(Transaction::select('created_at')->orderBy('created_at', 'desc')->first()->created_at));
        if($check_alarm != $request->last_donate) {
            return ['status'=>'ON', 'last_donate'=>$check_alarm];
        } else {
            return ['status'=>'OFF'];
        }
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
            $chat    = 'Terimakasih dermawan *'.ucwords(trim($donatur->name)).'*.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Aamiin.
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
     * Datatables Donate
     */
    public function datatablesDonate(Request $request)
    {
        // if ($request->ajax()) {
            $data = Transaction::select('transaction.*', 'donatur.name as name', 'donatur.telp', 'program.title', 'payment_type.name as payment_name')
                    ->join('program', 'program.id', 'transaction.program_id')
                    ->join('donatur', 'donatur.id', 'transaction.donatur_id')
                    ->join('payment_type', 'payment_type.id', 'transaction.payment_type_id');

            if(isset($request->need_fu)) {
                if($request->need_fu==1) {
                    $data = $data->where('transaction.status', '<>', 'success');
                }
            }

            if(isset($request->day5)) {
                if($request->day5==1) {
                    $data = $data->where('transaction.created_at', '>', date('Y-m-d H:i:s', strtotime(date('Y-m-d').'-5 day')));
                }
            }

            $data = $data->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('invoice', function($row){
                    $content = TrackingVisitor::where('program_id', $row->program_id)->where('ref_code', $row->ref_code)
                                ->where('created_at', 'like', date('Y-m-d H:i', strtotime($row->created_at)).'%')
                                ->where('payment_type_id', $row->payment_type_id)->where('nominal', $row->nominal)
                                ->where('page_view', 'invoice')->whereNotNull('utm_content')->first();
                    if(isset($content->utm_content)) {
                        $content = ' - '.$content->utm_content;
                    } else {
                        $content = '';
                    }

                    if(!is_null($row->ref_code) && $row->ref_code!='') {
                        $ref_code = ' <span class="badge badge-info">'.$row->ref_code.$content.'</span>';
                    } else {
                        $ref_code = '';
                    }
                    return $row->invoice_number.'<br>'.$row->payment_name.$ref_code;
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
                        $param  = $row->id.", '".$row->status."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
                        $status = '<div id="status_'.$row->id.'">Rp. '.number_format($row->nominal_final).'<br><span class="badge badge-success modal_status" style="cursor:pointer" onclick="editStatus('.$param.')">Sudah Dibayar</span></div>';
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

    /**
     * Display a listing of the resource.
     */
    public function donatePerdonatur(Request $request)
    {
        $donatur = \App\Models\Donatur::findOrFail($request->id);
        return view('admin.transaction.perdonatur', compact('donatur'));
    }

    /**
     * Datatables Fundraiser Dashboard
     */
    public function datatablesFundraiserDashboard(Request $request)
    {
        // if ($request->ajax()) {
            // if(isset($request->date)) {
            //     $date_now       = date('Y-m-d', strtotime($request->date));
            //     $date_yesterday = date('Y-m-d', strtotime($request->date.'-1 days'));
            // } else {
                $date_now       = date('Y-m-d');
                $date_yesterday = date('Y-m-d', strtotime($date_now .'-1 days'));
            // }

            $data = Transaction::where('created_at', '>=', $date_yesterday)->where('created_at', '<=', $date_now)->whereNotNull('ref_code')
                    ->select('ref_code')->groupBy('ref_code');
            $data = $data->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    // if(isset($request->date)) {
                    //     $date_now       = date('Y-m-d', strtotime($request->date));
                    // } else {
                        $date_now       = date('Y-m-d');
                    // }
                    $total_amount     = Transaction::where('ref_code', $row->ref_code)->sum('nominal_final');
                    $total_amount_now = Transaction::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)->sum('nominal_final');
                    $total_paid       = Transaction::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)->where('status', 'success')
                                        ->sum('nominal_final');
                    $avg_amount       = Transaction::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)->avg('nominal_final');
                    return '<b>'.$row->ref_code.'</b><br>Today : Rp.'.number_format($total_amount_now).'<br>Dibayar : Rp.'.number_format($total_paid).'<br>Rata2 : Rp.'.number_format($avg_amount).'<br>Semua : Rp.'.number_format($total_amount);
                })
                ->addColumn('total', function($row){
                    // if(isset($request->date)) {
                    //     $date_now       = date('Y-m-d', strtotime($request->date));
                    // } else {
                        $date_now       = date('Y-m-d');
                    // }
                    $view    = TrackingVisitor::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)
                                ->where('page_view', 'landing_page')->count();
                    $amount  = TrackingVisitor::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)
                                ->where('page_view', 'amount')->count();
                    $payment = TrackingVisitor::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)
                                ->where('page_view', 'payment_type')->count();
                    $form    = TrackingVisitor::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)
                                ->where('page_view', 'form')->count();
                    $donasi  = Transaction::where('created_at', 'like', $date_now.'%')->where('ref_code', $row->ref_code)->count();

                    return 'View : '.number_format($view).'<br>Klik Donasi : '.number_format($amount).'<br>Pilih Payment : '.number_format($payment).'<br>Form : '.number_format($form).'<br>Donasi : '.number_format($donasi);
                })
                ->addColumn('sesi1', function($row){
                    return 'View : <br>Klik Donasi : <br>Pilih Payment : <br>Form : <br>Donasi : ';
                })
                ->addColumn('sesi2', function($row){
                    return 'View : <br>Klik Donasi : <br>Pilih Payment : <br>Form : <br>Donasi : ';
                })
                ->addColumn('sesi3', function($row){
                    return 'View : <br>Klik Donasi : <br>Pilih Payment : <br>Form : <br>Donasi : ';
                })
                ->addColumn('sesi4', function($row){
                    return 'View : <br>Klik Donasi : <br>Pilih Payment : <br>Form : <br>Donasi : ';
                })
                ->rawColumns(['name', 'total', 'sesi1', 'sesi2', 'sesi3', 'sesi4'])
                ->make(true);
        // }
    }


    /**
     * Auto FU Donasi yg belum dibayar dan sudah pernah chat 1 kali, jadi ini chat ke dua kalinya yg dijalankan melalui CronJob
     */
    public function donateFu2Sc()
    {
        $now        = date('Y-m-d H:i:s');
        $now        = date('Y-m-d H:i:s', strtotime($now.'-1 days'));
        $date_start = date('Y-m-d H:i:s', strtotime($now.'-10 minutes'));
        $date_end   = date('Y-m-d H:i:s', strtotime($now.'+10 minutes'));

        $trans = Transaction::where('status', '<>', 'success')->where('created_at', '>=', $date_start)->where('created_at', '<=', $date_end)
                ->orderBy('created_at', 'asc')->get();
        $chat_count = 0;

        foreach ($trans as $key => $v) {
            if($chat_count<5) {         // agar 4 chat maksimal dalam 1 waktu 
                $count_fu = \App\Models\Chat::where('type', 'fu_trans')->where('transaction_id', $v->id)->select('id')->count();
                if($count_fu==1){

                    // kirim chat FU ke-2
                    $program = Program::where('id', $v->program_id)->select('id', 'title')->first();
                    $donatur = Donatur::where('id', $v->donatur_id)->select('id', 'name', 'telp')->first();
                    $name    = ', *'.ucwords(trim($donatur->name)).'* ';

                    $chat    = 'Selangkah lagi kebaikan Anda'.$name.'akan dirasakan untuk program
*'.ucwords($program->title).'*
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($v->nominal_final)).'*

bisa melalui
Transfer BSI - 7233152069
Transfer BRI - 041001000888302
Transfer BNI - 7060505013
Transfer Mandiri - 1370022225276
a/n Yayasan Bantu Bersama Sejahtera

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

                    (new WaBlastController)->sentWA($donatur->telp, $chat, 'fu_trans', $v->id, $donatur->id, $program->id);

                    // count kirim chat, agar tidak lebih dari 4 chat dalam 1 waktu kirim
                    $chat_count++;
                }
            }
        }
        echo "Finish";
    }
}
