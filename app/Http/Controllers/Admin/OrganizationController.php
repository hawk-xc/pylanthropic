<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Organization;
use DataTables;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.org.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.org.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'status'  => 'required',
            'phone'   => 'required',
            'mail'    => 'required',
            'logo'    => 'required',
            'about'   => 'required'
        ]);

        try {
            $data               = new Organization;
            $data->name         = $request->name;
            $data->uuid         = date('ymdhis');
            $data->phone        = $request->phone;
            $data->email        = $request->mail;
            $data->password     = '-';
            $data->address      = (isset($request->address) && $request->address!='') ? $request->addres : 'Indonesia';
            $data->about        = $request->about;
            $data->status       = $request->status;
            $data->pic_fullname = $request->pic_name;
            $data->pic_nik      = $request->pic_nik;
            $data->created_by   = Auth::user()->id;

            $filename           = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
            $filename           = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            $filet              = $request->file('logo');
            $filename_logo      = 'logo_'.$filename.'.'.$filet->getClientOriginalExtension();
            $filet->storeAs('public/images/fundraiser', $filename_logo, 'public_uploads');
            $data->logo         = $filename_logo;

            if ($request->filled('pic_image')) {
                $filet              = $request->file('pic_image');
                $filename_vr        = 'verified_'.$filename.'.'.$filet->getClientOriginalExtension();
                $filet->storeAs('public/images/fundraiser', $filename_vr, 'public_uploads');
                $data->pic_image    = $filename_vr;
            }

            $data->save();

            return redirect()->back()->with('success', 'Berhasil tambah data lembaga');
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
    public function edit(string $id)
    {
        $data = Organization::findOrFail($id);
        return view('admin.org.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required',
            'status'  => 'required',
            'phone'   => 'required',
            'mail'    => 'required',
            'about'   => 'required'
        ]);

        try {
            $data               = Organization::findOrFail($id);
            $data->name         = $request->name;
            $data->phone        = $request->phone;
            $data->email        = $request->mail;
            // $data->password     = '-';
            $data->about        = $request->about;
            $data->status       = $request->status;
            $data->pic_fullname = $request->pic_name;
            $data->pic_nik      = $request->pic_nik;
            $data->updated_by   = Auth::user()->id;
            $data->updated_at   = date('Y-m-d H:i:s');

            if(isset($request->address) && $request->address!='') {
                $data->address   = $request->address;
            }

            $filename            = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
            $filename            = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            if ($request->filled('pic_image')) {
                $filet           = $request->file('logo');
                $filename_logo   = 'logo_'.$filename.'.'.$filet->getClientOriginalExtension();
                $filet->storeAs('public/images/fundraiser', $filename_logo, 'public_uploads');
                $data->logo      = $filename_logo;
            }

            if ($request->filled('pic_image')) {
                $filet           = $request->file('pic_image');
                $filename_vr     = 'verified_'.$filename.'.'.$filet->getClientOriginalExtension();
                $filet->storeAs('public/images/fundraiser', $filename_vr, 'public_uploads');
                $data->pic_image = $filename_vr;
            }

            $data->save();

            return redirect()->back()->with('success', 'Berhasil update data program');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update, ada kesalahan teknis');
        }
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
    public function orgDatatables(Request $request)
    {
        // if ($request->ajax()) {
            $data         = Organization::orderBy('name', 'DESC');

            if(isset($request->name)) {
                if($request->name!='') {
                    $data = $data->where('name', 'like', '%'.urldecode($request->name).'%');
                }
            }

            if(isset($request->phone)) {
                if($request->phone!='') {
                    $data = $data->where('phone', 'like', '%'.urldecode($request->phone).'%');
                }
            }

            if(isset($request->email)) {
                if($request->email!='') {
                    $data = $data->where('email', 'like', '%'.urldecode($request->email).'%');
                }
            }

            if(isset($request->about)) {
                if($request->about!='') {
                    $data = $data->where('about', 'like', '%'.urldecode($request->about).'%');
                }
            }

            if(isset($request->status)) {
                if($request->status!='') {
                    $data = $data->where('status', 'like', '%'.urldecode($request->status).'%');
                }
            }

            $order_column = $request->input('order.0.column');
            $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

            $count_total  = $data->count();

            $search       = $request->input('search.value');

            $count_filter = $count_total;
            if($search != ''){
                $data     = $data->where(function ($q) use ($search){
                            $q->where('name', 'like', '%'.$search.'%')
                                ->orWhere('phone', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%')
                                ->orWhere('address', 'like', '%'.$search.'%')
                                ->orWhere('status', 'like', '%'.$search.'%')
                                ->orWhere('about', 'like', '%'.$search.'%');
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
                ->addColumn('name', function($row){
                    if($row->status=='verified') {
                        $status = '<span class="badge badge-sm badge-success"><i class="fa fa-check"></i> Personal</span>';
                    } elseif($row->status=='verif_org') {
                        $status = '<span class="badge badge-sm badge-success"><i class="fa fa-check"></i> Lembaga</span>';
                    } elseif($row->status=='banned') {
                        $status = '<span class="badge badge-sm badge-danger"><i class="fa fa-times"></i> Banned</span>';
                    } else {
                        $status = '<span class="badge badge-sm badge-info"><i class="fa fa-question"></i> Belum</span>';
                    }
                    return ucwords($row->name).'<br>'.$status;
                })
                ->addColumn('contact', function($row){
                    $telp  = $row->phone;
                    $email = $row->email;

                    return $telp.'<br>'.$email;
                })
                ->addColumn('summary', function($row){
                    $count_program   = \App\Models\Program::where('organization_id', $row->id)->count('id');
                    $sum_donate_paid = \App\Models\Transaction::join('program', 'program.id', '=', 'program_id')
                                        ->where('organization_id', $row->id)->where('transaction.status', 'success')->sum('nominal_final');
                    return number_format($count_program, 0, ',', '.').' Program <br>Rp. '.number_format($sum_donate_paid, 0, ',', '.');
                })
                ->addColumn('action', function($row){
                    return '<a href="'.route('adm.organization.edit', $row->id).'" target="_blank" class="edit btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>';
                })
                ->rawColumns(['name', 'action', 'contact', 'summary'])
                ->make(true);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function select2(Request $request)
    {
        $data = Organization::query();
        $last_page = null;

        if($request->has('search') && $request->search != ''){
            // Apply search param
            $data = $data->where('name', 'like', '%'.$request->search.'%');
        }

        if($request->has('page')){
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Data Fetched',
            'data'       => $data->get(),
            'extra_data' => [
                'last_page' => $last_page,
            ]
        ]);
    }
}
