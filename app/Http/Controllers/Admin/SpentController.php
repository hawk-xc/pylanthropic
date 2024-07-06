<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Program;
use App\Models\Transaction;
use App\Models\TrackingVisitor;

use DataTables;

class SpentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.program_spent');
    }


    /**
     * Show Donate from datatable program
     */
    public function spentDatatables(Request $request)
    {
        $data = \App\Models\ProgramSpend::select('program_spend.*', 'program.title as program_title')
                ->join('program', 'program.id', 'program_spend.program_id')->orderBy('created_at', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

        $count_total  = $data->count();

        $search       = $request->input('search.value');

        $count_filter = $count_total;
        if($search != ''){
            $data     = $data->where(function ($q) use ($search){
                        $q->where('program_spend.title', 'like', '%'.$search.'%')
                            ->orWhere('program.title', 'like', '%'.$search.'%')
                            ->orWhere('program.slug', 'like', '%'.$search.'%')
                            ->orWhere('desc_publish', 'like', '%'.$search.'%')
                            ->orWhere('program_spend.nominal_request', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                            ->orWhere('program_spend.nominal_approved', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                            ->orWhere('type', 'like', '%'.$search.'%')
                            ->orWhere('status', 'like', '%'.$search.'%')
                            ->orWhere('date_request', 'like', '%'.$search.'%')
                            ->orWhere('date_approved', 'like', '%'.$search.'%');
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
            ->addColumn('program_title', function($row){
                return ucwords($row->program_title);
            })
            ->addColumn('nominal', function($row){
                return '<i class="fa fa-file-signature icon-gradient bg-happy-green"></i> '.number_format($row->nominal_request).'<br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> '.number_format($row->nominal_approved);
            })
            ->addColumn('date', function($row){
                return '<i class="fa fa-file-signature icon-gradient bg-happy-green"></i> '.date('Y-m-d H:i', strtotime($row->date_request)).'<br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> '.date('Y-m-d H:i', strtotime($row->date_approved));
            })
            ->addColumn('status', function($row){
                if($row->type == 'ads') {
                    $type = '<span class="badge badge-info badge-sm">ADS</span>';
                } elseif($row->type == 'operational') {
                    $type = '<span class="badge badge-warning badge-sm">OPERASIONAL</span>';
                } elseif($row->type == 'payment_fee') {
                    $type = '<span class="badge badge-primary badge-sm">PAYMENT FEE</span>';
                } else {  // others
                    $type = '<span class="badge badge-success badge-sm">OTHERS</span>';
                }

                if($row->status == 'draft') {
                    $status = '<span class="badge badge-info badge-sm">PENGAJUAN</span>';
                } elseif($row->status == 'process') {
                    $status = '<span class="badge badge-warning badge-sm">DIPROSES</span>';
                } elseif($row->status == 'done') {
                    $status = '<span class="badge badge-success badge-sm">SELESAI</span>';
                } elseif($row->status == 'cancel') {
                    $status = '<span class="badge badge-secondary badge-sm">DIBATALKAN</span>';
                } else {  // reject
                    $status = '<span class="badge badge-danger badge-sm">DITOLAK</span>';
                }

                return $type.' '.$status;
            })
            ->addColumn('action', function($row){
                return '-';
            })
            ->rawColumns(['date', 'program_title', 'nominal', 'status', 'action'])
            ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function submitSpend(Request $request)
    {
        $request->validate([
            'title'      => 'required|string',
            'id_program' => 'required|numeric',
            'date_time'  => 'required',
            'nominal'    => 'required'
        ]);

        $data                   = new \App\Models\ProgramSpend;
        $data->program_id       = $request->id_program;
        $data->title            = trim($request->title);
        $data->nominal_request  = str_replace('.', '', $request->nominal);
        $data->nominal_approved = str_replace('.', '', $request->nominal);
        $data->date_request     = $request->date_time.':00';
        $data->date_approved    = $request->date_time.':00';
        $data->approved_by      = 1;
        $data->type             = 'ads';
        $data->is_operational   = 1;
        $data->status           = 'done';
        $data->desc_request     = trim($request->title);
        $data->save();

        echo "success";
        // return redirect()->back();
    }

}
