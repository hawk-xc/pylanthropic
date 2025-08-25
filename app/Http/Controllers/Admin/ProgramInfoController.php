<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Program;
use App\Models\ProgramInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

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
                        <form action="' . $url_delete . '" method="POST" class="d-inline" id="delete-form-'.$row->id.'">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" class="btn btn-danger btn-xs mb-1 delete-btn" data-id="'.$row->id.'" title="Delete"><i class="fa fa-trash"></i></button>
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
            'program_id' => 'required|exists:program,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_publish' => 'required|boolean',
        ]);

        try {
            $data = new ProgramInfo();
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
        $programInfo = ProgramInfo::findOrFail($id);
        return view('admin.program-info.edit', compact('programInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'program_id' => 'required|exists:program,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_publish' => 'required|boolean',
        ]);

        
        try {
            $data = ProgramInfo::findOrFail($id);
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
}