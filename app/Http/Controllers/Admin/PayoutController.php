<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Payout;

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
            'program_id'        => 'required|numeric',
            'nominal_request'   => 'required',
            'nominal_approved'  => 'required',
            'desc_request'      => 'required|string',
            'date_paid'         => 'nullable|date',
            'file_submit'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'file_paid'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'file_accepted'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024'
        ], [
            'program_id.required'       => 'Program harus diisi',
            'desc_request.required'     => 'Deskripsi harus diisi',
            'nominal_request.required'  => 'Nominal harus diisi',
            'nominal_approved.required' => 'Nominal harus diisi',
            'date_paid.date'            => 'Format tanggal salah',
            'file_submit.file'          => 'Format file salah, format yang didukung jpg, jpeg, png, pdf',
            'file_paid.file'            => 'Format file salah, format yang didukung jpg, jpeg, png, pdf',
            'file_accepted.file'        => 'Format file salah, format yang didukung jpg, jpeg, png, pdf',
            'file_submit.max'           => 'Ukuran file maksimal 1MB',
            'file_paid.max'             => 'Ukuran file maksimal 1MB',
            'file_accepted.max'         => 'Ukuran file maksimal 1MB'
        ]);

        try {
            $program = \App\Models\Program::find($request->program_id);
            if (!$program) {
                return back()->with('message', ['status' => 'error', 'message' => 'Program tidak ditemukan.']);
            }
            $payoutPath = 'images/program/payout/' . $program->slug;

            $data                   = new Payout;
            $data->program_id       = $request->program_id;
            $data->nominal_request  = str_replace('.', '', $request->nominal_request);
            $data->nominal_approved = str_replace('.', '', $request->nominal_approved);
            $data->desc_request     = $request->desc_request;
            $data->status           = $request->status;

            if($request->filled('date_paid')) {
                $data->paid_at      = $request->date_paid;
            }

            foreach (['file_submit', 'file_paid', 'file_accepted'] as $fileField) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    $extension = strtolower($file->getClientOriginalExtension());

                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $filename = $fileField . '.png';
                        $fullPath = $payoutPath . '/' . $filename;
                        
                        $image = Image::make($file->getRealPath())
                            ->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('png');
                        
                        Storage::disk('public_uploads')->put($fullPath, (string) $image);
                        $data->{$fileField} = $fullPath;
                    } else if ($extension === 'pdf') {
                        $filename = $fileField . '.pdf';
                        $fullPath = $payoutPath . '/' . $filename;
                        Storage::disk('public_uploads')->putFileAs($payoutPath, $file, $filename);
                        $data->{$fileField} = $fullPath;
                    }
                }
            }

            $data->save();

            return redirect(route('adm.payout.index'))->with('message', [
                'status' => 'success', 
                'message' => 'Berhasil tambah data Penyaluran Program'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('message', [
                'status' => 'error', 
                'message' => 'Gagal tambah data Penyaluran Program: ' . $e->getMessage()
            ]);
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
            'program_id'        => 'required|numeric',
            'desc_request'      => 'required|string',
            'nominal_request'  => 'required',
            'nominal_approved' => 'required',
            'date_paid'         => 'nullable|date',
            'file_submit'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_paid'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_accepted'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        try {
            $data = Payout::findOrFail($id);
            
            $programId = $request->program_id ?? $data->program_id;
            $program = \App\Models\Program::find($programId);
            if (!$program) {
                return back()->with('message', ['status' => 'error', 'message' => 'Program tidak ditemukan.']);
            }
            $payoutPath = 'images/program/payout/' . $program->slug;

            $data->program_id       = $request->program_id;
            $data->nominal_request  = str_replace('.', '', $request->nominal_request);
            $data->nominal_approved = str_replace('.', '', $request->nominal_approved);
            $data->desc_request     = $request->desc_request;
            $data->status           = $request->status;

            if($request->filled('date_paid')) {
                $data->paid_at      = $request->date_paid;
            } else {
                $data->paid_at      = null;
            }

            foreach (['file_submit', 'file_paid', 'file_accepted'] as $fileField) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    $extension = strtolower($file->getClientOriginalExtension());
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isPdf = $extension === 'pdf';

                    if ($isImage || $isPdf) {
                        $newExtension = $isImage ? 'png' : 'pdf';
                        $baseFilename = $fileField;
                        
                        $counter = 0;
                        $filename = $baseFilename . '.' . $newExtension;
                        $fullPath = $payoutPath . '/' . $filename;

                        // Check if file exists and increment counter
                        while (Storage::disk('public_uploads')->exists($fullPath)) {
                            $counter++;
                            $filename = $baseFilename . '_' . $counter . '.' . $newExtension;
                            $fullPath = $payoutPath . '/' . $filename;
                        }

                        if ($isImage) {
                            $image = Image::make($file->getRealPath())
                                ->resize(800, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); })
                                ->encode('png');
                            Storage::disk('public_uploads')->put($fullPath, (string) $image);
                        } else { // PDF
                            Storage::disk('public_uploads')->putFileAs($payoutPath, $file, $filename);
                        }
                        
                        $data->{$fileField} = $fullPath;
                    }
                }
            }

            $data->updated_at  = date('Y-m-d H:i:s');
            $data->save();

            return redirect(route('adm.payout.index'))->with('message', [
                'status' => 'success', 
                'message' => 'Berhasil update data Penyaluran Program'
            ]);
        } catch (\Exception $e) {
            return back()->with('message', [
                'status' => 'error', 
                'message' => 'Gagal update data Penyaluran Program: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Show Donate from datatable program
     */
    public function payoutDatatables(Request $request)
    {
        $data = Payout::select('payout.*', 'program.title as program_title', 'slug')
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
                $view = '<a href="'.route("program.payout", $row->slug).'" target="_blank" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>';
                $edit = '<a href="'.route("adm.payout.edit", $row->id).'" target="_blank" class="edit btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>';
                return $view.'<br>'.$edit;
            })
            ->rawColumns(['title', 'date', 'program_title', 'nominal', 'status', 'action'])
            ->make(true);
    }

    public function storeImagecontent(Request $request)
    {
        $number = $request->number;
        $number = str_replace('img', '', $number);

        $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);
        $file = $request->file('file');
        $filename = $filename . '_' . $number . '.jpg';

        $image = Image::make($file->getRealPath())
            ->fit(580, 780)
            ->encode('jpg', 80);

        $path = 'images/program/payout/content/' . $filename;

        Storage::disk('public_uploads')->put($path, $image->stream());

        $link_img = url('public/' . $path);

        return [
            'link' => $link_img,
            'full' => '<img data-original="' . $link_img . '" class="lazyload" alt="' . ucwords($request->name) . ' - Bantubersama.com" />',
        ];
    }

    public function uploadImageContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'program_title' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => $validator->errors()->first()]], 400);
        }

        try {
            $file = $request->file('file');
            $programTitle = $request->input('program_title');
            $programSlug = 'unnamed'; // Default value

            if ($programTitle) {
                $program = \App\Models\Program::where('title', $programTitle)->first();
                if ($program) {
                    $programSlug = $program->slug;
                }
            }

            // Path structure
            $contentDir = 'images/program/payout/' . $programSlug . '/content';
            $baseName = $programSlug;

            // Find existing files to determine the counter
            $existingFiles = Storage::disk('public_uploads')->files($contentDir);
            $existingPngFiles = preg_grep('/' . preg_quote($baseName, '/') . '_(\d+)\.png$/', $existingFiles);

            $counter = 1;
            if (!empty($existingPngFiles)) {
                natsort($existingPngFiles);
                $lastFile = end($existingPngFiles);
                preg_match('/_(\d+)\.png$/', $lastFile, $matches);
                if (isset($matches[1])) {
                    $counter = (int) $matches[1] + 1;
                }
            }

            $filename = $baseName . '_' . $counter . '.png';
            $path = $contentDir . '/' . $filename;

            $image = Image::make($file->getRealPath())
                ->fit(580, 780)
                ->encode('png');

            Storage::disk('public_uploads')->put($path, $image->stream());

            $url = url('public/' . $path);

            return response()->json(['location' => $url]);
        } catch (\Exception $e) {
            return response()->json(['error' => ['message' => $e->getMessage()]], 500);
        }
    }
}
