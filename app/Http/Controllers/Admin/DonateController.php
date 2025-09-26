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
        $donate_today_paid_count   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')
                                    ->count();
        $donate_today_paid_sum     = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')
                                    ->sum('nominal_final');
        $donate_today_unpaid_count = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')
                                    ->where('status', '<>', 'success')->count();
        $donate_today_unpaid_sum   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')
                                    ->where('status', '<>', 'success')->sum('nominal_final');
        $visit_lp                  = TrackingVisitor::where('created_at', 'like', date('Y-m-d').'%')->where('page_view', 'landing_page')->count();
        $all_paid                  = Transaction::where('status', 'success')->sum('nominal_final');
        $paid_now                  = Transaction::where('status', 'success')->where('created_at', 'like', date('Y-m').'%')->sum('nominal_final');
        $avg_paid_now              = $paid_now/date('d');

        // $last_donate = date('Y-m-d H:i:s', strtotime($request->last_donate));
        $check_alarm = date('Y-m-d H:i:s', strtotime(Transaction::select('created_at')->orderBy('created_at', 'desc')->first()->created_at));
        if($check_alarm != $request->last_donate) {
            return [
                'status'=>'ON', 'last_donate'=>$check_alarm, 
                'paid_count'   => number_format($donate_today_paid_count),
                'paid_sum'     => number_format($donate_today_paid_sum),
                'unpaid_count' => number_format($donate_today_unpaid_count),
                'unpaid_sum'   => number_format($donate_today_unpaid_sum),
                'visit_lp'     => number_format($visit_lp),
                'avg_paid_now' => str_replace(',', '.', number_format($avg_paid_now)),
                'paid_now'     => str_replace(',', '.', number_format($paid_now)),
                'all_paid'     => str_replace(',', '.', number_format($all_paid)),
                'sum_today'    => str_replace(',', '.', number_format($donate_today_paid_sum+$donate_today_unpaid_sum)),
                'count_today'  => str_replace(',', '.', number_format($donate_today_paid_count+$donate_today_unpaid_count)),
            ];
        } else {
            return [
                'status'=>'OFF',
                'paid_count'   => number_format($donate_today_paid_count),
                'paid_sum'     => number_format($donate_today_paid_sum),
                'unpaid_count' => number_format($donate_today_unpaid_count),
                'unpaid_sum'   => number_format($donate_today_unpaid_sum),
                'visit_lp'     => number_format($visit_lp),
                'avg_paid_now' => str_replace(',', '.', number_format($avg_paid_now)),
                'paid_now'     => str_replace(',', '.', number_format($paid_now)),
                'all_paid'     => str_replace(',', '.', number_format($all_paid)),
                'sum_today'    => str_replace(',', '.', number_format($donate_today_paid_sum+$donate_today_unpaid_sum)),
                'count_today'  => str_replace(',', '.', number_format($donate_today_paid_count+$donate_today_unpaid_count)),
            ];
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
    public function destroy(Request $request, string $id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $donaturId = $transaction->donatur_id;

            if ($request->input('delete_type') === 'with_donatur') {
                $transactionCount = Transaction::where('donatur_id', $donaturId)->count();
                if ($transactionCount <= 1) {
                    Donatur::findOrFail($donaturId)->delete();
                }
            }

            $transaction->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
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
        
        if($request->mutation_id!=='' && $request->mutation_id>0) {
            \App\Models\CheckMutation::where('id', $request->mutation_id)->update(['transaction_id'=> $id_trans]);
        }

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
    public function autoAdd(Request $request)
    {
        $mutation_id = $request->mutation_id;

        $mutation = \App\Models\CheckMutation::where('id', $request->mutation_id)->first();

        if(isset($mutation->id)) {
            // insert table transaction
            $id_increment          = Transaction::select('id')->orderBy('id', 'DESC')->first();
            $invoice               = 'INV-'.date('Ymd').(isset($id_increment->id)?$id_increment->id+1:1);

            if($mutation->bank_type=='bni') {
                $bank_type         = 19;
            } elseif($mutation->bank_type=='bsi') {
                $bank_type         = 2;
            } elseif($mutation->bank_type=='bri') {
                $bank_type         = 4;
            } elseif($mutation->bank_type=='mandiri') {
                $bank_type         = 3;
            } elseif($mutation->bank_type=='bca') {
                $bank_type         = 1;
            } elseif($mutation->bank_type=='qris') {
                $bank_type         = 5;
            } else {
                $bank_type         = 6;
            }

            $data                  = new Transaction;
            $data->program_id      = 22;
            $data->donatur_id      = 1487;
            $data->invoice_number  = $invoice;
            $data->nominal         = $mutation->amount;
            $data->status          = 'success';
            $data->nominal_code    = 0;
            $data->nominal_final   = $mutation->amount;
            $data->message         = '';
            $data->payment_type_id = $bank_type;
            $data->is_show_name    = 0;
            $data->midtrans_token  = '';
            $data->midtrans_url    = '';
            $data->user_agent      = '';
            $data->ref_code        = '';
            $data->save();
            $id_trans              = $data->id;

            // update data mutation with new id trans
            $mutation->update(['transaction_id'=> $id_trans]);

            return array('status'=>'success', 'nominal'=>'Rp.'.number_format($mutation->amount));
        } else {
            return array('status'=>'fail');
        }
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
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($trans->nominal_final)).'*';

            $chat2   = $chat.'

atau melalui :
Transfer BSI - 7855555667
Transfer BRI - 041001001007307
Transfer BNI - 1859941829
Transfer Mandiri - 1370023737469
Transfer BCA - 4561399292
a/n *Yayasan Bantu Beramal Bersama*

melalui QRIS
https://bantubersama.com/public/QRIS.png

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';
                
//                     $chat2   = $chat.'

// atau melalui :
// Transfer BSI - 7233152069
// Transfer BRI - 041001000888302
// Transfer BNI - 7060505013
// Transfer Mandiri - 1370022225276
// Transfer BCA - 4561363999
// a/n *Yayasan Bantu Bersama Sejahtera*

// melalui QRIS
// https://bantubersama.com/public/qris-babe.png

// Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
// Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

            // (new WaBlastController)->sentWA($donatur->telp, $chat);
            (new WaBlastController)->sentWA($donatur->telp, $chat2, 'fu_trans', $trans->id, $donatur->id, $program->id);
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
                    ->join('payment_type', 'payment_type.id', 'transaction.payment_type_id')
                    ->orderBy('transaction.created_at', 'DESC');

            if(isset($request->need_fu)) {
                if($request->need_fu==1) {
                    $data = $data->where('transaction.status', '<>', 'success');
                }
            }

            if(isset($request->day5)) {     // show max 5 day ago
                if($request->day5==1) {
                    $data = $data->where('transaction.created_at', '>', date('Y-m-d H:i:s', strtotime(date('Y-m-d').'-5 day')));
                }
            }

            if(isset($request->day1)) {     // just yesterday, today not include
                if($request->day1==1) {
                    $data = $data->where('transaction.created_at', 'like', date('Y-m-d', strtotime(date('Y-m-d').'-1 day')).'%');
                }
            }

            if(isset($request->bca)) {     // bca
                if($request->bca==1) {
                    // $data = $data->where('transaction.payment_type_id', 1);
                    $data = $data->where('payment_type.key', 'like', 'tf_bca%');
                }
            }

            if(isset($request->bni)) {     // bni
                if($request->bni==1) {
                    // $data = $data->where('transaction.payment_type_id', 19);
                    $data = $data->where('payment_type.key', 'like', 'tf_bni%');
                }
            }

            if(isset($request->bsi)) {     // bsi
                if($request->bsi==1) {
                    // $data = $data->where('transaction.payment_type_id', 2);
                    $data = $data->where('payment_type.key', 'like', 'tf_bsi%');
                }
            }

            if(isset($request->bri)) {     // bri
                if($request->bri==1) {
                    // $data = $data->where('transaction.payment_type_id', 4);
                    $data = $data->where('payment_type.key', 'like', 'tf_bri%');
                }
            }

            if(isset($request->qris)) {     // qris
                if($request->qris==1) {
                    $data = $data->where('transaction.payment_type_id', 5);
                }
            }

            if(isset($request->gopay)) {     // gopay
                if($request->gopay==1) {
                    $data = $data->where('transaction.payment_type_id', 6);
                }
            }

            if(isset($request->mandiri)) {     // mandiri
                if($request->mandiri==1) {
                    // $data = $data->where('transaction.payment_type_id', 3);
                    $data = $data->where('payment_type.key', 'like', 'tf_mandiri%');
                }
            }

            if(isset($request->ref_code)) {     // ref_code
                if($request->ref_code!='') {
                    $data = $data->where('transaction.ref_code', $request->ref_code);
                }
            }

            if(isset($request->donatur_name)) {     // donatur name
                if($request->donatur_name!='') {
                    $data = $data->where('donatur.name', 'like', '%'.urldecode($request->donatur_name).'%');
                }
            }

            if(isset($request->donatur_telp)) {     // donatur telp
                if($request->donatur_telp!='') {
                    $data = $data->where('donatur.telp', 'like', '%'.$request->donatur_telp.'%');
                }
            }

            if(isset($request->filter_nominal)) {     // transaction nominal
                if($request->filter_nominal!='') {
                    $data = $data->where('transaction.nominal_final', 'like', '%'.str_replace([',', '.'], '', $request->filter_nominal).'%');
                }
            }

            if(isset($request->program_id)) {     // program id
                if($request->program_id!='') {
                    $data = $data->where('transaction.program_id', $request->program_id);
                }
            }

            if(isset($request->donatur_id)) {     // Donatur ID
                if($request->donatur_id!='') {
                    $data = $data->where('donatur.id', $request->donatur_id);
                }
            }

            if(isset($request->status) && $request->status != '') {
                $data = $data->where('transaction.status', $request->status);
            }

            $order_column = $request->input('order.0.column');
            $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

            $count_total  = $data->count();

            $search       = $request->input('search.value');

            $count_filter = $count_total;
            if($search != ''){
                $data     = $data->where(function ($q) use ($search){
                            $q->where('transaction.created_at', 'like', '%'.$search.'%')
                                ->orWhere('transaction.invoice_number', 'like', '%'.$search.'%')
                                ->orWhere('transaction.nominal_final', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                                ->orWhere('transaction.ref_code', 'like', '%'.$search.'%')
                                ->orWhere('program.title', 'like', '%'.$search.'%')
                                ->orWhere('payment_type.name', 'like', '%'.$search.'%')
                                ->orWhere('donatur.name', 'like', '%'.$search.'%')
                                ->orWhere('donatur.telp', 'like', '%'.$search.'%');
                            });
                $count_filter = $data->count();
            }

            $pageSize     = ($request->length) ? $request->length : 10;
            $start        = ($request->start) ? $request->start : 0;

            $data->skip($start)->take($pageSize);

            $data         = $data->get();
            return Datatables::of($data)
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->setOffset($start)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    if ($row->status == 'cancel' || $row->status == 'draft') {
                        return '<input type="checkbox" class="delete-checkbox" value="'.$row->id.'" data-name="'.e(ucwords($row->name)).'" data-nominal="Rp. '.number_format($row->nominal_final).'" data-invoice="'.$row->invoice_number.'">';
                    }
                    return '';
                })
                ->addColumn('invoice', function($row){
                    // $content = TrackingVisitor::where('program_id', $row->program_id)->where('ref_code', $row->ref_code)
                    //             ->where('created_at', 'like', date('Y-m-d H:i', strtotime($row->created_at)).'%')
                    //             ->where('payment_type_id', $row->payment_type_id)->where('nominal', $row->nominal)
                    //             ->where('page_view', 'invoice')->whereNotNull('utm_content')->first();
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
                        $param = $row->id.", '".str_replace("'", "", ucwords($row->name))."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
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
                    $donaturBtn = '<a href="'.route('adm.donatur.show', $row->donatur_id).'" class="btn btn-info btn-sm mb-1" title="Lihat Donatur"><i class="fa fa-user"></i></a>';
                
                    $deleteBtn = '';
                    if ($row->status == 'cancel' || $row->status == 'draft') {
                        $deleteBtn = '<button class="btn btn-danger btn-sm mb-1" title="Hapus" onclick="openDeleteModal('.$row->id.')"><i class="fa fa-trash"></i></button>';
                    }

                    return $donaturBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['checkbox', 'action', 'invoice', 'nominal_final', 'name', 'created_at'])
                ->make(true);
        // }
    }

    /**
     * Remove multiple specified resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (empty($ids)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dipilih.'], 400);
            }

            $transactions = Transaction::whereIn('id', $ids)->where(function ($query) {
                $query->where('status', 'cancel')->orWhere('status', 'draft');
            })->get();

            if ($request->input('delete_type') === 'with_donatur') {
                $donaturIdsToDelete = [];
                foreach ($transactions as $transaction) {
                    $donaturId = $transaction->donatur_id;
                    // Count transactions for this donor that are NOT in the current bulk delete list
                    $transactionCount = Transaction::where('donatur_id', $donaturId)
                                                    ->whereNotIn('id', $ids)
                                                    ->count();
                    if ($transactionCount == 0) {
                        // If the donor has no other transactions left, mark for deletion
                        $donaturIdsToDelete[] = $donaturId;
                    }
                }
                if (!empty($donaturIdsToDelete)) {
                    Donatur::whereIn('id', array_unique($donaturIdsToDelete))->delete();
                }
            }

            // Delete the transactions
            if ($transactions->count() > 0) {
                Transaction::whereIn('id', $ids)->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Datatables Donate
     */
    public function datatablesDonateMutation(Request $request)
    {
        // if ($request->ajax()) {
            $data = \App\Models\CheckMutation::where('description', 'not like', 'QRISOffUs%')->where('description', 'not like', 'QRISOnUs%')
                    ->orderBy('created_at', 'DESC');

            if(isset($request->type)) {
                if($request->need_fu=='out') {
                    $data = $data->where('mutation_type', 'db');
                } else {
                    $data = $data->where('mutation_type', 'cr');
                }
            } else {
                $data = $data->where('mutation_type', 'cr');
            }

            if(isset($request->today)) {     // just today
                if($request->today==1) {
                    $data = $data->where('mutation_date', 'like', date('Y-m-d').'%');
                }
            }

            if(isset($request->day1)) {     // just yesterday, today not include
                if($request->day1==1) {
                    $data = $data->where('mutation_date', '>', date('Y-m-d H:i:s', strtotime(date('Y-m-d').'-1 day')));
                }
            }

            if(isset($request->day2)) {     // show max 5 day ago
                if($request->day2==1) {
                    $data = $data->where('mutation_date', '>', date('Y-m-d H:i:s', strtotime(date('Y-m-d').'-2 day')));
                }
            }

            if(isset($request->notmatch)) {     // show not match with transaction
                if($request->notmatch==1) {
                    $data = $data->whereNull('transaction_id');
                }
            }

            if(isset($request->bca)) {     // bca
                if($request->bca==1) {
                    $data = $data->where('bank_type', 'bca');
                }
            }

            if(isset($request->bni)) {     // bni
                if($request->bni==1) {
                    $data = $data->where('bank_type', 'bni');
                }
            }

            if(isset($request->bsi)) {     // bsi
                if($request->bsi==1) {
                    $data = $data->where('bank_type', 'bsi');
                }
            }

            if(isset($request->bri)) {     // bri
                if($request->bri==1) {
                    $data = $data->where('bank_type', 'bri');
                }
            }

            if(isset($request->mandiri)) {     // mandiri
                if($request->mandiri==1) {
                    $data = $data->where('bank_type', 'mandiri');
                }
            }

            if(isset($request->filter_nominal)) {     // mutation nominal
                if($request->filter_nominal!='') {
                    $data = $data->where('amount', 'like', '%'.str_replace([',', '.'], '', $request->filter_nominal).'%');
                }
            }

            $order_column = $request->input('order.0.column');
            $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

            $count_total  = $data->count();

            $search       = $request->input('search.value');

            $count_filter = $count_total;
            if($search != ''){
                $data     = $data->where(function ($q) use ($search){
                            $q->where('mutation_date', 'like', '%'.$search.'%')
                                ->orWhere('bank_type', 'like', '%'.$search.'%')
                                ->orWhere('amount', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                                ->orWhere('description', 'like', '%'.$search.'%');
                            });
                $count_filter = $data->count();
            }

            $pageSize     = ($request->length) ? $request->length : 10;
            $start        = ($request->start) ? $request->start : 0;

            $data->skip($start)->take($pageSize);

            $data         = $data->get();
            return Datatables::of($data)
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->setOffset($start)
                ->addIndexColumn()
                ->addColumn('nominal', function($row){
                    if(!is_null($row->transaction_id) && $row->transaction_id>0) {
                        $nominal = '<span class="badge badge-pills badge-success">'.str_replace(',', '.', number_format($row->amount)).'</span>';
                    } else {
                        $nominal = '<span class="modal_check" data-id="'.$row->id.'">'.str_replace(',', '.', number_format($row->amount)).'</span>';
                        $nominal .= ' <span class="copy_id_mutation" onclick="copyIDMutation('.$row->id.')"><i class="fa fa-copy fa-xs"></i></span>';
                    }
                    return $nominal.'<br>'.strtoupper($row->bank_type);
                })
                ->addColumn('date_desc', function($row){
                    if(!is_null($row->transaction_id) && $row->transaction_id>0) {
                        $add_trans = '';
                    } else {
                        $url        = route('adm.donate.manual_add', $row->id);
                        $add_trans  = ' <span class="copy_id_mutation" onclick="addTrans('.$row->id.')"><i class="fa fa-plus-square"></i></span>';
                        $add_trans .= ' <a href="'.$url.'" target="_blank" class="add_trans"><i class="fa fa-share-square"></i></a>';
                    }
                    
                    $desc = '<span style="font-size:11px;">'.$row->description.'</span>';
                    return date('Y-m-d H:i:s', strtotime($row->mutation_date)).$add_trans.'<br>'.$desc;
                })
                ->rawColumns(['nominal', 'date_desc'])
                ->make(true);
        // }
    }

    /**
     * Manual Add Transaction from check mutation
     */
    public function manualAdd(Request $request)
    {
        echo "under maintenance";
    }

    /**
     * Datatables Donate Qurban
     */
    public function datatablesDonateQurban(Request $request)
    {
        // if ($request->ajax()) {
            $data = Transaction::select('transaction.*', 'donatur.name as name', 'donatur.telp', 'payment_type.name as payment_name')
                    ->join('donatur', 'donatur.id', 'transaction.donatur_id')
                    ->join('payment_type', 'payment_type.id', 'transaction.payment_type_id')
                    ->where('transaction.program_id', 1)
                    ->orderBy('transaction.created_at', 'DESC');

            if(isset($request->need_fu)) {
                if($request->need_fu==1) {
                    $data = $data->where('transaction.status', '<>', 'success');
                }
            }

            if(isset($request->day5)) {     // show max 5 day ago
                if($request->day5==1) {
                    $data = $data->where('transaction.created_at', '>', date('Y-m-d H:i:s', strtotime(date('Y-m-d').'-5 day')));
                }
            }

            if(isset($request->day1)) {     // just yesterday, today not include
                if($request->day1==1) {
                    $data = $data->where('transaction.created_at', 'like', date('Y-m-d', strtotime(date('Y-m-d').'-1 day')).'%');
                }
            }

            if(isset($request->bca)) {     // bca
                if($request->bca==1) {
                    $data = $data->where('transaction.payment_type_id', 1);
                }
            }

            if(isset($request->bni)) {     // bni
                if($request->bni==1) {
                    $data = $data->where('transaction.payment_type_id', 19);
                }
            }

            if(isset($request->bsi)) {     // bsi
                if($request->bsi==1) {
                    $data = $data->where('transaction.payment_type_id', 2);
                }
            }

            if(isset($request->bri)) {     // bri
                if($request->bri==1) {
                    $data = $data->where('transaction.payment_type_id', 4);
                }
            }

            if(isset($request->qris)) {     // qris
                if($request->qris==1) {
                    $data = $data->where('transaction.payment_type_id', 5);
                }
            }

            if(isset($request->gopay)) {     // gopay
                if($request->gopay==1) {
                    $data = $data->where('transaction.payment_type_id', 6);
                }
            }

            if(isset($request->mandiri)) {     // mandiri
                if($request->mandiri==1) {
                    $data = $data->where('transaction.payment_type_id', 3);
                }
            }

            if(isset($request->donatur_name)) {     // donatur name
                if($request->donatur_name!='') {
                    $data = $data->where('donatur.name', 'like', '%'.urldecode($request->donatur_name).'%');
                }
            }

            if(isset($request->donatur_telp)) {     // donatur telp
                if($request->donatur_telp!='') {
                    $data = $data->where('donatur.telp', 'like', '%'.$request->donatur_telp.'%');
                }
            }

            if(isset($request->filter_nominal)) {     // transaction nominal
                if($request->filter_nominal!='') {
                    $data = $data->where('transaction.nominal_final', 'like', '%'.str_replace([',', '.'], '', $request->filter_nominal).'%');
                }
            }

            $order_column = $request->input('order.0.column');
            $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

            $count_total  = $data->count();

            $search       = $request->input('search.value');

            $count_filter = $count_total;
            if($search != ''){
                $data     = $data->where(function ($q) use ($search){
                            $q->where('transaction.created_at', 'like', '%'.$search.'%')
                                ->orWhere('transaction.invoice_number', 'like', '%'.$search.'%')
                                ->orWhere('transaction.nominal_final', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                                ->orWhere('payment_type.name', 'like', '%'.$search.'%')
                                ->orWhere('donatur.name', 'like', '%'.$search.'%')
                                ->orWhere('donatur.telp', 'like', '%'.$search.'%');
                            });
                $count_filter = $data->count();
            }

            $pageSize     = ($request->length) ? $request->length : 10;
            $start        = ($request->start) ? $request->start : 0;

            $data->skip($start)->take($pageSize);

            $data         = $data->get();
            return Datatables::of($data)
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->setOffset($start)
                ->addIndexColumn()
                ->addColumn('invoice', function($row){
                    // $content = TrackingVisitor::where('program_id', $row->program_id)->where('ref_code', $row->ref_code)
                    //             ->where('created_at', 'like', date('Y-m-d H:i', strtotime($row->created_at)).'%')
                    //             ->where('payment_type_id', $row->payment_type_id)->where('nominal', $row->nominal)
                    //             ->where('page_view', 'invoice')->whereNotNull('utm_content')->first();
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
                        $param = $row->id.", '".str_replace("'", "", ucwords($row->name))."', 'Rp. ".str_replace(',', '.', number_format($row->nominal_final))."'";
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
                ->addColumn('qurban_type', function($row){
                    if($row->user_agent==1){
                        return '<b>Kambing</b><br>'.$row->message;
                    } elseif($row->user_agent==2){
                        return '<b>Domba</b><br>'.$row->message;
                    } elseif($row->user_agent==3){
                        return '<b>Sapi 1/7</b><br>'.$row->message;
                    } else {
                        return '<b>Sapi Utuh</b><br>'.$row->message;
                    }
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
                ->rawColumns(['action', 'invoice', 'nominal_final', 'name', 'qurban_type', 'created_at'])
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

            $data = Transaction::where('created_at', '>=', $date_yesterday.' 00:00:00')->where('created_at', '<=', $date_now.' 23:59:59')
                    ->whereNotNull('ref_code')->select('ref_code')->groupBy('ref_code');
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
Dengan donasi yang Anda berikan sebesar *Rp '.str_replace(',', '.', number_format($v->nominal_final)).'*';

                    $chat2   = $chat.'

atau melalui :
Transfer BSI - 7855555667
Transfer BRI - 041001001007307
Transfer BNI - 1859941829
Transfer Mandiri - 1370023737469
Transfer BCA - 4561399292
a/n *Yayasan Bantu Beramal Bersama*

melalui QRIS
https://bantubersama.com/public/QRIS.png

Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';
                
//                     $chat2   = $chat.'

// atau melalui :
// Transfer BSI - 7233152069
// Transfer BRI - 041001000888302
// Transfer BNI - 7060505013
// Transfer Mandiri - 1370022225276
// Transfer BCA - 4561363999
// a/n *Yayasan Bantu Bersama Sejahtera*

// melalui QRIS
// https://bantubersama.com/public/qris-babe.png

// Kebaikan Anda sangat berarti bagi kami yang membutuhkan.
// Semoga Anda sekeluarga selalu diberi kesehatan dan dilimpahkan rizki yang berkah. Aamiin';

                    (new WaBlastController)->sentWA($donatur->telp, $chat2, 'fu_trans', $v->id, $donatur->id, $program->id);

                    // count kirim chat, agar tidak lebih dari 4 chat dalam 1 waktu kirim
                    $chat_count++;
                }
            }
        }
        echo "Finish";
    }

    /**
     * Donate x Mutation to match
     */
    public function donateMutation()
    {
        $last_donate               = Transaction::select('created_at')->orderBy('created_at', 'desc')->first()->created_at;
        $donate_today_paid_count   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')
                                    ->count();
        $donate_today_paid_sum     = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')
                                    ->sum('nominal_final');
        $donate_today_unpaid_count = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')
                                    ->where('status', '<>', 'success')->count();
        $donate_today_unpaid_sum   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d').'%')
                                    ->where('status', '<>', 'success')->sum('nominal_final');
        $visit_lp                  = TrackingVisitor::where('created_at', 'like', date('Y-m-d').'%')->where('page_view', 'landing_page')->count();
        $sum_paid_now              = Transaction::where('status', 'success')->where('created_at', 'like', date('Y-m').'%')->sum('nominal_final');
        $sum_paid                  = Transaction::where('status', 'success')->sum('nominal_final');
        $dn                        = date('Y-m-d');
        $donate_yest1_paid_count   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')
                                    ->where('status', 'success')->count();
        $donate_yest1_paid_sum     = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')
                                    ->where('status', 'success')->sum('nominal_final');
        $donate_yest1_unpaid_count = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')
                                    ->where('status', '<>', 'success')->count();
        $donate_yest1_unpaid_sum   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')
                                    ->where('status', '<>', 'success')->sum('nominal_final');
        $donate_yest2_paid_count   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')
                                    ->where('status', 'success')->count();
        $donate_yest2_paid_sum     = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')
                                    ->where('status', 'success')->sum('nominal_final');
        $donate_yest2_unpaid_count = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')
                                    ->where('status', '<>', 'success')->count();
        $donate_yest2_unpaid_sum   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')
                                    ->where('status', '<>', 'success')->sum('nominal_final');
        $donate_yest3_paid_count   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')
                                    ->where('status', 'success')->count();
        $donate_yest3_paid_sum     = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')
                                    ->where('status', 'success')->sum('nominal_final');
        $donate_yest3_unpaid_count = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')
                                    ->where('status', '<>', 'success')->count();
        $donate_yest3_unpaid_sum   = Transaction::select('created_at')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')
                                    ->where('status', '<>', 'success')->sum('nominal_final');


        return view('admin.transaction.donate_mutation', compact('last_donate', 'donate_today_paid_count', 'donate_today_paid_sum',
            'donate_today_unpaid_count', 'donate_today_unpaid_sum', 'visit_lp', 'sum_paid_now', 'sum_paid',
            'donate_yest1_paid_count', 'donate_yest1_paid_sum', 'donate_yest1_unpaid_count', 'donate_yest1_unpaid_sum',
            'donate_yest2_paid_count', 'donate_yest2_paid_sum', 'donate_yest2_unpaid_count', 'donate_yest2_unpaid_sum',
            'donate_yest3_paid_count', 'donate_yest3_paid_sum', 'donate_yest3_unpaid_count', 'donate_yest3_unpaid_sum'));
    }

    /**
     * Donate Qurban
     */
    public function donateQurban()
    {
        $last_donate               = Transaction::select('created_at')->where('program_id', 1)->orderBy('created_at', 'desc')->first()->created_at;
        $donate_today_paid_count   = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')->count();
        $donate_today_paid_sum     = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('created_at', 'like', date('Y-m-d').'%')->where('status', 'success')->sum('nominal_final');
        $donate_today_unpaid_count = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('created_at', 'like', date('Y-m-d').'%')->where('status', '<>', 'success')->count();
        $donate_today_unpaid_sum   = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('created_at', 'like', date('Y-m-d').'%')->where('status', '<>', 'success')->sum('nominal_final');
        $donate_total_paid_count   = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('status', 'success')->count();
        $donate_total_paid_sum     = Transaction::select('created_at')->where('program_id', 1)
                                    ->where('status', 'success')->sum('nominal_final');

        // Kambing
        $donate_kambing_count      = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 1)
                                    ->where('status', 'success')->count();
        $donate_kambing_paid_sum   = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 1)
                                    ->where('status', 'success')->sum('nominal_final');
        // Domba
        $donate_domba_paid_count   = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 2)
                                    ->where('status', 'success')->count();
        $donate_domba_paid_sum     = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 2)
                                    ->where('status', 'success')->sum('nominal_final');
        // Sapi 1/7
        $donate_sapi17_paid_count  = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 3)
                                    ->where('status', 'success')->count();
        $donate_sapi17_paid_sum    = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 3)
                                    ->where('status', 'success')->sum('nominal_final');
        // Sapi Utuh
        $donate_sapi_paid_count    = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 4)
                                    ->where('status', 'success')->count();
        $donate_sapi_paid_sum      = Transaction::select('created_at')->where('program_id', 1)->where('user_agent', 4)
                                    ->where('status', 'success')->sum('nominal_final');

        return view('admin.transaction.donate_qurban', compact('last_donate', 'donate_today_paid_count', 'donate_today_paid_sum',
                'donate_today_unpaid_count', 'donate_today_unpaid_sum', 'donate_total_paid_count', 'donate_total_paid_sum',
                'donate_kambing_count', 'donate_kambing_paid_sum', 'donate_domba_paid_count', 'donate_domba_paid_sum', 'donate_sapi17_paid_count',
                'donate_sapi17_paid_sum', 'donate_sapi_paid_count', 'donate_sapi_paid_sum'));
    }
    
}