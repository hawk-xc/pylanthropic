<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Payout;

use DataTables;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.payout.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payout.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program'          => 'required|numeric',
            'description'      => 'required|string',
            'nominal_request'  => 'required',
            'nominal_approved' => 'required'
        ]);

        try {
            $data                   = new Payout;
            $data->program_id       = $request->program;
            $data->nominal_request  = str_replace('.', '', $request->nominal_request);
            $data->nominal_approved = str_replace('.', '', $request->nominal_approved);
            $data->desc_request     = $request->description;
            $data->status           = $request->status;

            if($request->program!='') {
                $data->paid_at      = $request->date_paid;
            }
            // else paid_at = null

            // upload file_submit
            if ($request->hasFile('file_submit')) { 
                $file1              = $request->file('file_submit');
                $filename           = time().'_'.$file1->getClientOriginalName();
                $file1->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_submit  = $filename;
            }

            // upload file_paid
            if ($request->hasFile('file_paid')) { 
                $file2              = $request->file('file_paid');
                $filename           = time().'_'.$file2->getClientOriginalName();
                $file2->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_paid    = $filename;
            }

            // upload file_accepted
            if ($request->hasFile('file_accepted')) { 
                $file3               = $request->file('file_accepted');
                $filename            = time().'_'.$file3->getClientOriginalName();
                $file3->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_accepted = $filename;
            }

            $data->save();

            return redirect()->back()->with('success', 'Berhasil tambah data Penyaluran Proagram');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal tambah, ada kesalahan teknis');
        }
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
    public function edit($id)
    {
        $data = Payout::select('payout.*', 'title')->where('payout.id', $id)->join('program', 'payout.program_id', 'program.id')->first();

        return view('admin.payout.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'program'          => 'required|numeric',
            'description'      => 'required|string',
            'nominal_request'  => 'required',
            'nominal_approved' => 'required'
        ]);

        try {
            $data                   = Payout::findOrFail($id);
            $data->program_id       = $request->program;
            $data->nominal_request  = str_replace('.', '', $request->nominal_request);
            $data->nominal_approved = str_replace('.', '', $request->nominal_approved);
            $data->desc_request     = $request->description;
            $data->status           = $request->status;

            if($request->program!='') {
                $data->paid_at      = $request->date_paid;
            }
            // else paid_at = null

            // upload file_submit
            if ($request->hasFile('file_submit')) { 
                $file1              = $request->file('file_submit');
                $filename           = time().'_'.$file1->getClientOriginalName();
                $file1->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_submit  = $filename;
            }

            // upload file_paid
            if ($request->hasFile('file_paid')) { 
                $file2              = $request->file('file_paid');
                $filename           = time().'_'.$file2->getClientOriginalName();
                $file2->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_paid    = $filename;
            }

            // upload file_accepted
            if ($request->hasFile('file_accepted')) { 
                $file3               = $request->file('file_accepted');
                $filename            = time().'_'.$file3->getClientOriginalName();
                $file3->storeAs('public/images/payout', $filename, 'public_uploads');
                $data->file_accepted = $filename;
            }

            $data->updated_at  = date('Y-m-d H:i:s');
            $data->save();

            return redirect()->back()->with('success', 'Berhasil update data Penyaluran Proagram');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update, ada kesalahan teknis');
        }
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
                if(!is_null($row->paid_at)) {
                    $paid_at = date('Y-m-d H:i', strtotime($row->paid_at));
                } else {
                    $paid_at = 'not set';
                }

                return '<i class="fa fa-file-signature icon-gradient bg-happy-green"></i> '.date('Y-m-d H:i', strtotime($row->created_at)).'<br><i class="fa fa-check-double icon-gradient bg-happy-green"></i> '.$paid_at;
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
                $view = '<a href="" href="'.route("adm.payout.show", $row->id).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>';
                $edit = '<a href="'.route("adm.payout.edit", $row->id).'" target="_blank" class="edit btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>';
                return $view.'<br>'.$edit;
            })
            ->rawColumns(['title', 'date', 'program_title', 'nominal', 'status', 'action'])
            ->make(true);
    }


}
