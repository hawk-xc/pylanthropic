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
}
