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
                ->addColumn('want_wa', function($row){
                    if($row->want_to_contact==1) {
                        $want_contact = '<span class="badge badge-pill badge-success">Mau</span>';
                    } else {
                        $want_contact = '<span class="badge badge-pill badge-secondary">Belum</span>';
                    }

                    if($row->wa_inactive_since===null) {
                        $wa_status = '<span class="badge badge-pill badge-success">Active</span>';
                    } else {
                        $wa_status = '<span class="badge badge-pill badge-danger">Inactive</span>';
                    }

                    return $want_contact.' '.$wa_status;
                })
                ->addColumn('created_at', function($row){
                    return date('Y-m-d H:i', strtotime($row->created_at));
                })
                ->addColumn('sum_donate', function($row){
                    return number_format(5656);
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'want_wa', 'sum_donate'])
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
