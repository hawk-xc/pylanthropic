<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Program;
use App\Models\ProgramInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class ProgramInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProgramInfo::select('program_info.*', 'program.title as program_title')
                ->join('program', 'program.id', '=', 'program_info.program_id');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url_edit = route('adm.program-info.edit', $row->id);
                    $url_delete = route('adm.program-info.destroy', $row->id);
                    $actionBtn =
                        '<a href="' . $url_edit . '" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
                        <form action="' . $url_delete . '" method="POST" class="d-inline" id="delete-form-' . $row->id . '">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-danger btn-xs mb-1 delete-btn" data-id="' . $row->id . '" title="Delete"><i class="fa fa-trash"></i></button>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.program-info.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program-info.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'program_id' => 'required|exists:program,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_publish' => 'required|boolean',
        ]);

        try {
            $data = new ProgramInfo();
            $data->date = $request->date;
            $data->program_id = $request->program_id;
            $data->title = $request->title;
            $data->content = $request->content;
            $data->is_publish = $request->is_publish;
            $data->save();

            return redirect()->route('adm.program-info.index')->with('success', 'Kabar terbaru berhasil ditambahkan.');
        } catch (Exception $err) {
            return redirect()->route('adm.program-info.index')->with('error', $err->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $programInfo = ProgramInfo::findOrFail($id);
        return view('admin.program-info.edit', compact('programInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required|date',
            'program_id' => 'required|exists:program,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_publish' => 'required|boolean',
        ]);


        try {
            $data = ProgramInfo::findOrFail($id);
            $data->date = $request->date;
            $data->program_id = $request->program_id;
            $data->title = $request->title;
            $data->content = $request->content;
            $data->is_publish = $request->is_publish;
            $data->save();

            return redirect()->route('adm.program-info.index')->with('success', 'Kabar terbaru berhasil diupdate.');
        } catch (Exception $err) {
            return redirect()->route('adm.program-info.index')->with('error', $err->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $programInfo = ProgramInfo::findOrFail($id);
        $programInfo->delete();

        return redirect()->route('adm.program-info.index')->with('success', 'Kabar terbaru berhasil dihapus.');
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

        $path = 'images/program/program_update/' . $filename;

        Storage::disk('public_uploads')->put($path, $image->stream());

        // PERBAIKAN: Menghapus 'public/' dari URL yang dibuat.
        // Sebelumnya: $link_img = url('public/' . $path);
        $link_img = url($path);

        return [
            'link' => $link_img,
            'full' => '<img data-original="' . $link_img . '" class="lazyload" alt="' . ucwords($request->name) . ' - Bantusesama.com" />',
        ];
    }

    public function uploadImageContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => $validator->errors()->first(),
                ],
            ], 400);
        }

        try {
            $file = $request->file('file');
            $programTitle = $request->input('program_title');

            $baseName = str_replace([' ', '-', '&', ':'], '_', trim($programTitle));
            $baseName = preg_replace('/[^A-Za-z0-9\_]/', '', $baseName);

            // Path relatif untuk penyimpanan
            $contentDir = 'images/program/program_update';

            // Cari file yang sudah ada
            $existingFiles = Storage::disk('public_uploads')->files($contentDir);
            $existingFiles = preg_grep('/' . preg_quote($baseName, '/') . '_(\d+)\.jpg$/', $existingFiles);

            // Hitung counter berikutnya
            $counter = 1;
            if (!empty($existingFiles)) {
                natsort($existingFiles);
                $lastFile = end($existingFiles);
                preg_match('/_(\d+)\.jpg$/', $lastFile, $matches);
                if (isset($matches[1])) {
                    $counter = (int) $matches[1] + 1;
                }
            }

            // Generate nama file baru
            $filename = $baseName . '_' . $counter . '.jpg';
            $path = $contentDir . '/' . $filename;

            // Proses dan simpan gambar
            $image = Image::make($file->getRealPath())
                ->fit(580, 780)
                ->encode('jpg', 80);

            Storage::disk('public_uploads')->put($path, $image->stream());

            // PERBAIKAN: Menghapus 'public/' dari URL yang dibuat.
            // Sebelumnya: $url = url('public/' . $path);
            $url = url($path);

            return response()->json([
                'location' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }
}
