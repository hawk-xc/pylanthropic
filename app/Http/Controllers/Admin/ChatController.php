<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use DataTables;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.chat.index');
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
     * Datatables Chat
     */
    public function datatablesChat()
    {
        // if ($request->ajax()) {
            $data = Chat::latest()->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('program', function($row){
                    if(isset($row->program_id) && !is_null($row->program_id)) {
                        $program_name = \App\Models\Program::where('id', $row->program_id)->select('title')->first();
                        return ucwords($program_name->title);
                    } else {
                        return 'No Program';
                    }
                })
                ->addColumn('transaction', function($row){
                    if(isset($row->transaction_id) && !is_null($row->transaction_id)) {
                        $trans = \App\Models\Transaction::where('id', $row->transaction_id)->first();
                        if($trans->status=='draft'){
                            $trans_status = '<span class="badge badge-warning">Belum Dibayar</span>';
                        } elseif ($trans->status=='success') {
                            $trans_status = '<span class="badge badge-success">Sudah Dibayar</span>';
                        } else {
                            $trans_status = '<span class="badge badge-secondary">Dibatalkan</span>';
                        }
                        return $trans->invoice_number.'<br>Rp.'.number_format($trans->nominal_final).'<br>'.$trans_status;
                    } else {
                        return 'No Transaksi';
                    }
                })
                ->addColumn('created_at', function($row){
                    return date('Y-m-d H:i', strtotime($row->created_at));
                })
                ->rawColumns(['program', 'transaction', 'created_at'])
                ->make(true);
        // }
    }

}
