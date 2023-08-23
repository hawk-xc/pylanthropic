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

            $curl  = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/check_number');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT,30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'token'   => 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3',
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
