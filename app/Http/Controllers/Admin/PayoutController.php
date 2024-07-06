<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Program;
use App\Models\Payout;

use DataTables;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.program_payout');
    }


    /**
     * Show Donate from datatable program
     */
    public function payoutDatatables(Request $request)
    {
        $data = Payout::select('payout.*', 'program.title as program_title')
                ->join('program', 'program.id', 'payout.program_id')->orderBy('created_at', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

        $count_total  = $data->count();

        $search       = $request->input('search.value');

        $count_filter = $count_total;
        if($search != ''){
            $data     = $data->where(function ($q) use ($search){
                        $q->where('payout.desc_request', 'like', '%'.$search.'%')
                            ->orWhere('program.title', 'like', '%'.$search.'%')
                            ->orWhere('program.slug', 'like', '%'.$search.'%')
                            ->orWhere('payout.nominal_request', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                            ->orWhere('payout.nominal_approved', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                            ->orWhere('status', 'like', '%'.$search.'%')
                            ->orWhere('paid_at', 'like', '%'.$search.'%');
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
            ->addColumn('title', function($row){
                return ucwords($row->desc_request);
            })
            ->addColumn('program_title', function($row){
                return ucwords($row->program_title);
            })
            ->addColumn('nominal', function($row){
                return '<i class="fa fa-file-signature icon-gradient bg-happy-green"></i> '.number_format($row->nominal_request).'<br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> '.number_format($row->nominal_approved);
            })
            ->addColumn('date', function($row){
                return '<i class="fa fa-file-signature icon-gradient bg-happy-green"></i> '.date('Y-m-d H:i', strtotime($row->created_at)).'<br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> '.date('Y-m-d H:i', strtotime($row->paid_at));
            })
            ->addColumn('status', function($row){
                if($row->status == 'request') {
                    $status = '<span class="badge badge-info badge-sm">PENGAJUAN</span>';
                } elseif($row->status == 'process') {
                    $status = '<span class="badge badge-warning badge-sm">DIPROSES</span>';
                } elseif($row->status == 'paid') {
                    $status = '<span class="badge badge-success badge-sm">SELESAI</span>';
                } elseif($row->status == 'cancel') {
                    $status = '<span class="badge badge-secondary badge-sm">DIBATALKAN</span>';
                } else {  // reject
                    $status = '<span class="badge badge-danger badge-sm">DITOLAK</span>';
                }

                return $status;
            })
            ->addColumn('action', function($row){
                return '-';
            })
            ->rawColumns(['title', 'date', 'program_title', 'nominal', 'status', 'action'])
            ->make(true);
    }


}
