<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donatur;
use DataTables;

class DonaturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.donatur.index');
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
     * Datatables Donatur
     */
    public function datatablesDonatur()
    {
        // if ($request->ajax()) {
            $data = Donatur::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = 'Mau';
                        $status_color = 'success';
                    } else {
                        $want_contact = 'Belum';
                        $status_color = 'warning';
                    }

                    if($row->wa_inactive_since===null) {
                        $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
                    } else {
                        $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('last_donate', function($row){
                    $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                    if($donate_last->count()>0) {
                        $donate_last  = $donate_last->first();
                        $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                        return 'Rp.'.number_format($donate_last->nominal_final).' '.date('d-m-Y H:i', strtotime($donate_last->created_at)).' ('.$donate_last->status.')<br>'.ucwords($program_name->title);
                    } else {
                        return 'Belum Pernah';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('donate_summary', function($row){
                    $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                    if($donate_sum->count()>0) {
                        $donate_sum_nominal = number_format($donate_sum->count()).' kali';
                        return $donate_sum_nominal.'<br><a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success" >Rp.'.number_format($donate_sum->sum('nominal_final')).'</a>';
                    } else {
                        return '0';
                    }
                })
                ->addColumn('chat', function($row){
                    $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                    if(!empty($chat->type)) {
                        if($chat->type=='fu_trans') {
                            $chat_type = 'FU Transaksi';
                        } elseif($chat->type=='thanks_trans') {
                            $chat_type = 'Setelah Transaksi';
                        } elseif($chat->type=='repeat_donate') {
                            $chat_type = 'Ajak Transaksi Ulang';
                        } else {
                            $chat_type = 'Info Umum';
                        }
                        return $chat_type.'<br>'.date('d-m-Y H:i', strtotime($chat->created_at));
                    } else {
                        return 'No Data';
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
                ->make(true);
        // }
    }

    /**
     * Datatables Donatur Dorman
     */
    public function datatablesDonaturDorman()
    {
        // if ($request->ajax()) {
            $data = Donatur::where('last_donate_paid', '<=', '2023-08-11 00:00:00')->where('want_to_contact', '1')
                    ->whereNull('wa_inactive_since')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = 'Mau';
                        $status_color = 'success';
                    } else {
                        $want_contact = 'Belum';
                        $status_color = 'warning';
                    }

                    if($row->wa_inactive_since===null) {
                        $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
                    } else {
                        $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('last_donate', function($row){
                    $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                    if($donate_last->count()>0) {
                        $donate_last  = $donate_last->first();
                        $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                        return 'Rp.'.number_format($donate_last->nominal_final).' '.date('d-m-Y H:i', strtotime($donate_last->created_at)).' ('.$donate_last->status.')<br>'.ucwords($program_name->title);
                    } else {
                        return 'Belum Pernah';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('donate_summary', function($row){
                    $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                    if($donate_sum->count()>0) {
                        $donate_sum_nominal = number_format($donate_sum->count()).' kali';
                        return $donate_sum_nominal.'<br><a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success" >Rp.'.number_format($donate_sum->sum('nominal_final')).'</a>';
                    } else {
                        return '0';
                    }
                })
                ->addColumn('chat', function($row){
                    $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                    if(!empty($chat->type)) {
                        if($chat->type=='fu_trans') {
                            $chat_type = 'FU Transaksi';
                        } elseif($chat->type=='thanks_trans') {
                            $chat_type = 'Setelah Transaksi';
                        } elseif($chat->type=='repeat_donate') {
                            $chat_type = 'Ajak Transaksi Ulang';
                        } else {
                            $chat_type = 'Info Umum';
                        }
                        return $chat_type.'<br>'.date('d-m-Y H:i', strtotime($chat->created_at));
                    } else {
                        return 'No Data';
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
                ->make(true);
        // }
    }

    /**
     * Datatables Donatur Dorman
     */
    public function datatablesDonaturTetap()
    {
        // if ($request->ajax()) {
            $data = Donatur::where('count_donate_paid', '>', '2')->where('want_to_contact', '1')->whereNull('wa_inactive_since')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = 'Mau';
                        $status_color = 'success';
                    } else {
                        $want_contact = 'Belum';
                        $status_color = 'warning';
                    }

                    if($row->wa_inactive_since===null) {
                        $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
                    } else {
                        $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('last_donate', function($row){
                    $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                    if($donate_last->count()>0) {
                        $donate_last  = $donate_last->first();
                        $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                        return 'Rp.'.number_format($donate_last->nominal_final).' '.date('d-m-Y H:i', strtotime($donate_last->created_at)).' ('.$donate_last->status.')<br>'.ucwords($program_name->title);
                    } else {
                        return 'Belum Pernah';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('donate_summary', function($row){
                    $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                    if($donate_sum->count()>0) {
                        $donate_sum_nominal = number_format($donate_sum->count()).' kali';
                        return $donate_sum_nominal.'<br><a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success" >Rp.'.number_format($donate_sum->sum('nominal_final')).'</a>';
                    } else {
                        return '0';
                    }
                })
                ->addColumn('chat', function($row){
                    $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                    if(!empty($chat->type)) {
                        if($chat->type=='fu_trans') {
                            $chat_type = 'FU Transaksi';
                        } elseif($chat->type=='thanks_trans') {
                            $chat_type = 'Setelah Transaksi';
                        } elseif($chat->type=='repeat_donate') {
                            $chat_type = 'Ajak Transaksi Ulang';
                        } else {
                            $chat_type = 'Info Umum';
                        }
                        return $chat_type.'<br>'.date('d-m-Y H:i', strtotime($chat->created_at));
                    } else {
                        return 'No Data';
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
                ->make(true);
        // }
    }

    /**
     * Datatables Donatur Sultan = 500ribu keatas total dibayar
     */
    public function datatablesDonaturSultan()
    {
        // if ($request->ajax()) {
            $data = Donatur::where('sum_donate_paid', '>=', '500000')->where('want_to_contact', '1')->whereNull('wa_inactive_since')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = 'Mau';
                        $status_color = 'success';
                    } else {
                        $want_contact = 'Belum';
                        $status_color = 'warning';
                    }

                    if($row->wa_inactive_since===null) {
                        $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
                    } else {
                        $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('last_donate', function($row){
                    $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                    if($donate_last->count()>0) {
                        $donate_last  = $donate_last->first();
                        $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                        return 'Rp.'.number_format($donate_last->nominal_final).' '.date('d-m-Y H:i', strtotime($donate_last->created_at)).' ('.$donate_last->status.')<br>'.ucwords($program_name->title);
                    } else {
                        return 'Belum Pernah';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('donate_summary', function($row){
                    $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                    if($donate_sum->count()>0) {
                        $donate_sum_nominal = number_format($donate_sum->count()).' kali';
                        return $donate_sum_nominal.'<br><a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success" >Rp.'.number_format($donate_sum->sum('nominal_final')).'</a>';
                    } else {
                        return '0';
                    }
                })
                ->addColumn('chat', function($row){
                    $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                    if(!empty($chat->type)) {
                        if($chat->type=='fu_trans') {
                            $chat_type = 'FU Transaksi';
                        } elseif($chat->type=='thanks_trans') {
                            $chat_type = 'Setelah Transaksi';
                        } elseif($chat->type=='repeat_donate') {
                            $chat_type = 'Ajak Transaksi Ulang';
                        } else {
                            $chat_type = 'Info Umum';
                        }
                        return $chat_type.'<br>'.date('d-m-Y H:i', strtotime($chat->created_at));
                    } else {
                        return 'No Data';
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
                ->make(true);
        // }
    }

    /**
     * Datatables Donatur Hampir = Pernah transaksi tapi belum pernah dibayar
     */
    public function datatablesDonaturHampir()
    {
        // if ($request->ajax()) {
            $data = Donatur::where('sum_donate_paid', '0')->where('want_to_contact', '1')->whereNull('wa_inactive_since')->latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('name', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = 'Mau';
                        $status_color = 'success';
                    } else {
                        $want_contact = 'Belum';
                        $status_color = 'warning';
                    }

                    if($row->wa_inactive_since===null) {
                        $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
                    } else {
                        $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
                    }
                    return ucwords($row->name).'<br>'.$telp;
                })
                ->addColumn('last_donate', function($row){
                    $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                    if($donate_last->count()>0) {
                        $donate_last  = $donate_last->first();
                        $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                        return 'Rp.'.number_format($donate_last->nominal_final).' '.date('d-m-Y H:i', strtotime($donate_last->created_at)).' ('.$donate_last->status.')<br>'.ucwords($program_name->title);
                    } else {
                        return 'Belum Pernah';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('donate_summary', function($row){
                    $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                    if($donate_sum->count()>0) {
                        $donate_sum_nominal = number_format($donate_sum->count()).' kali';
                        return $donate_sum_nominal.'<br><a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success" >Rp.'.number_format($donate_sum->sum('nominal_final')).'</a>';
                    } else {
                        return '0';
                    }
                })
                ->addColumn('chat', function($row){
                    $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                    if(!empty($chat->type)) {
                        if($chat->type=='fu_trans') {
                            $chat_type = 'FU Transaksi';
                        } elseif($chat->type=='thanks_trans') {
                            $chat_type = 'Setelah Transaksi';
                        } elseif($chat->type=='repeat_donate') {
                            $chat_type = 'Ajak Transaksi Ulang';
                        } else {
                            $chat_type = 'Info Umum';
                        }
                        return $chat_type.'<br>'.date('d-m-Y H:i', strtotime($chat->created_at));
                    } else {
                        return 'No Data';
                    }
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
                ->make(true);
        // }
    }

    /**
     * Display a listing of the resource.
     */
    public function dorman()
    {
        return view('admin.donatur.dorman');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function tetap()
    {
        return view('admin.donatur.tetap');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function sultan()
    {
        return view('admin.donatur.sultan');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function hampir()
    {
        return view('admin.donatur.hampir');
    }

    /**
     * Update summary donate last_donate_paid, count_donate_paid, sum)donate_paid
     */
    public function donateUpdate()
    {
        $last_donate_paid = Donatur::select('id')->whereNull('last_donate_paid')->orderBy('id','asc')->limit(1000)->get();
        foreach($last_donate_paid as $v){
            $trans = \App\Models\Transaction::where('donatur_id', $v->id)->where('status', 'success')->orderBy('created_at', 'DESC')->first();
            if( isset($trans->created_at) ) {
                Donatur::where('id', $v->id)->update([ 'last_donate_paid' => $trans->created_at ]);
            } else {
                Donatur::where('id', $v->id)->update([ 'last_donate_paid' => '2023-01-01 00:00:00' ]);
            }
        }
        echo 'FINISH LAST DONATE PAID';

        $count_donate_paid = Donatur::select('id')->whereNull('count_donate_paid')->orderBy('id','asc')->limit(1000)->get();
        foreach($count_donate_paid as $vc){
            $trans_c = \App\Models\Transaction::where('donatur_id', $vc->id)->where('status', 'success')->count();
            Donatur::where('id', $vc->id)->update([ 'count_donate_paid' => $trans_c ]);
        }
        echo '<br>FINISH COUNT DONATE PAID';

        $sum_donate_paid = Donatur::select('id')->whereNull('sum_donate_paid')->orderBy('id','asc')->limit(1000)->get();
        foreach($sum_donate_paid as $vs){
            $trans_s = \App\Models\Transaction::where('donatur_id', $vs->id)->where('status', 'success')
                        ->where('created_at', '<', '2023-09-01 00:00:00')->sum('nominal_final');
            Donatur::where('id', $vs->id)->update([ 'sum_donate_paid' => $trans_s ]);
        }
        echo '<br>FINISH SUM DONATE PAID';
    }

    /**
     * Cek WA Dorman
     */
    public function waDorman()
    {
        $data = Donatur::where('last_donate_paid', '<=', '2023-08-11 00:00:00')->where('last_donate_paid', '>=', '2023-03-01 00:00:00')
                // ->where('wa_campaign', '!=', 'dorman-25-08-2023')
                ->whereNull('wa_campaign')
                ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('last_donate_paid', 'asc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $trans = \App\Models\Transaction::select('program.id', 'title', 'slug')->join('program', 'program_id', 'program.id')
                    ->where('transaction.status', 'success')->where('donatur_id', $v->id)->orderBy('transaction.created_at', 'DESC')->first();

            $chat  = 'Perkenalkan saya Isna dari *Bantubersama*, semoga sehat selalu buat Kak *'.ucwords($v->name).'* aamiin..

Program yang Anda donasikan sebelumnya :
*'.ucwords($trans->title).'*

Masih terus berjalan hingga hari ini

Yuk kembali kita manfaatkan lagi kesempatan ini untuk terlibat aksi nyata dalam kebaikan.
Melalui link dibawah ini

https://bantubersama.com/'.$trans->slug.'

Kepedulian kita masih terus dinantikan, oleh mereka yang membutuhkan.';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => $token,
                'number'  => $telp,
                'message' => $chat,
                'date'    => date('Y-m-d'),
                'time'    => date('H:i'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp'        => $telp,
                'text'           => $chat,
                'token'          => $token,
                'vendor'         => 'RuangWA',
                'url'            => 'https://app.ruangwa.id/api/send_message',
                'type'           => 'repeat_donate',
                'transaction_id' => null,
                'donatur_id'     => $v->id,
                'program_id'     => $trans->id
            ]);
        }
        
        echo 'FINISH';
    }

    /**
     * Cek WA Dorman
     */
    public function waSummaryDonate()
    {
        $data = Donatur::where('sum_donate_paid', '>', 0)->whereNull('wa_campaign')
                ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('sum_donate_paid', 'desc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $nominal_final = \App\Models\Transaction::select('nominal_final')->where('created_at', '<', '2023-09-01 00:00:00')
                            ->where('transaction.status', 'success')->where('donatur_id', $v->id)->sum('nominal_final');

            $chat  = 'Salam peduli, sehat dan bahagia selalu buat Anda

Terima kasih atas donasi yang telah diberikan dan sudah menjadi bagian dari pelopor *Misi Kebaikan Bantubersama.com*

Rekap donasi Anda bulan Agustus 2023 sebesar *Rp.'.str_replace(',', '.', number_format($nominal_final)).'*
Semoga jiwa kepedulian dan komitmen membantu sesama terus membersamai Anda

Mari terus lanjutkan langkah positif ini untuk membantu sesama, kepedulian Anda masih terus dinantikan bagi mereka yang membutuhkan.

Yuk donasi kembali dengan klik tautan ini

https://bantubersama.com

Terimakash';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => $token,
                'number'  => $telp,
                'message' => $chat,
                'date'    => date('Y-m-d'),
                'time'    => date('H:i'),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp'        => $telp,
                'text'           => $chat,
                'token'          => $token,
                'vendor'         => 'RuangWA',
                'url'            => 'https://app.ruangwa.id/api/send_message',
                'type'           => 'info',
                'transaction_id' => null,
                'donatur_id'     => $v->id,
                'program_id'     => null
            ]);
        }
        
        echo 'FINISH';
    }

    /**
     * Cek WA Aktif
     */
    public function talentWACheck()
    {
        $data = Donatur::select('id', 'telp')->whereNull('wa_check')->whereNull('wa_inactive_since')->orderBy('id','asc')->limit(4)->get();

        foreach($data as $v){
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/check_number');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => $token,
                'number'  => $telp
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $response = json_decode($response);
            $now      = date('Y-m-d H:i:s');
            
            if($response->result=='true'){
                $update = Donatur::select('id')->where('id', $v->id);
                if($response->onwhatsapp=='true'){
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => null]);
                } else {
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => $now]);
                }
            }
        }
        
        echo 'FINISH';
    }
}
