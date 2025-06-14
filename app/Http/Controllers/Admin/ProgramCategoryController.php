<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramCategory;
use DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string|unique:program_category,slug',
            'logo_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ], [
            'title.required' => 'Judul harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'url.required' => 'URL harus diisi.',
            'url.string' => 'URL harus berupa teks.',
            'url.unique' => 'URL sudah digunakan.',
            'logo_image.image' => 'Logo harus berupa gambar.',
            'logo_image.mimes' => 'Logo harus berupa file bertipe: jpeg, png, jpg, gif, svg.',
            'logo_image.max' => 'Logo tidak boleh lebih besar dari 2048 kilobytes.',
        ]);

        $latestCategory = ProgramCategory::latest()->first();

        if ($latestCategory) {
            $sortNumber = $latestCategory->sort_number + 1;
        } else {
            $sortNumber = 1;
        }

        try {
            $programCategory = new ProgramCategory();
            $programCategory->name = $request->title;
            $programCategory->slug = Str::slug($request->url);
            $programCategory->sort_number = $sortNumber;
            $programCategory->created_by = auth()->user()->id;
            $programCategory->is_show = $request->has('is_show') ? 1 : 0;

            if ($request->hasFile('logo_image')) {
                $logoImage = $request->file('logo_image');
                $logoImageName = time() . '.' . $logoImage->getClientOriginalExtension();
                $logoImage->move(public_path('public/images/categories'), $logoImageName);

                // image name
                $programCategory->icon = $logoImageName;
            }

            $programCategory->save();

            return redirect()->route('adm.program-category.index')->with('success', 'Program Category berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('adm.program-category.index')->with('error', 'Terjadi kesalahan saat menambahkan Program Category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ProgramCategory::findOrFail($id);

        return view('admin.program-category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = ProgramCategory::findOrFail($id);

        return view('admin.program-category.edit', [
            'category' => $category,
        ]);
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
     * Remove the specified resource from storage.
     */
    public function select2(Request $request)
    {
        $data = ProgramCategory::query();
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

    public function datatablesProgramCategory(Request $request)
    {
        $data = ProgramCategory::withCount('programs')->orderBy('sort_number', 'asc');

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $data->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        // Filter berdasarkan is_show jika ada
        if ($request->has('is_show') && $request->is_show !== '') {
            $data->where('is_show', $request->is_show);
        }

        return DataTables::of($data)
            ->addColumn('name', function($row) {
                $icon = $row->icon ? '<i class="'.$row->icon.' mr-2"></i>' : '';
                $name = $row->name;

                // Cek jika created_at dalam 7 hari terakhir
                $isNew = $row->created_at >= now()->subDays(7);

                if ($isNew) {
                    $name .= ' <span class="badge badge-warning ml-2">Kategori Baru</span>';
                }

                return $icon . $name;
            })
            ->addColumn('is_show', function($row) {
                return $row->is_show
                    ? '<span class="badge badge-success">Ditampilkan</span>'
                    : '<span class="badge badge-danger">Tidak Ditampilkan</span>';
            })
            ->addColumn('program_count', function($row) {
                return '<a href="'.route('adm.program.index', ['category' => $row->id]).'" class="badge badge-info">'.$row->programs_count.' Program</a>';
            })
            ->addColumn('action', function($row) {
                $editUrl = route('adm.program-category.edit', $row->id);
                $linkUrl = url('/programs' . '?kategori=' . $row->slug);
                $showUrl = route('adm.program-category.show', $row->id);

                $btn_edit = '<a href="'.$editUrl.'" class="edit btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>';

                $btn_link = '<a href="'.$linkUrl.'" class="edit btn btn-info btn-xs" title="Link" target="_blank"><i class="fa fa-external-link-alt"></i></a>';

                $btn_show = '<a href="'.$showUrl.'" class="edit btn btn-info btn-xs" title="Show"><i class="fa fa-eye"></i></a>';

                return $btn_edit.' '.$btn_show.' '.$btn_link;
            })
            ->rawColumns(['name', 'is_show', 'program_count', 'action'])
            ->toJson();
    }

    /**
     * Store image content
     */
    public function checkUrl(Request $request)
    {
        $url = $request->url;
        $url = str_replace([' ', "'", '"', ',', ';', ':', '&'], '', $url);
        $url = preg_replace('/[^A-Za-z0-9\_-]/', '', $url);

        $cek = ProgramCategory::where('slug', $url)->select('id')->count();

        if($cek<1) {
            return 'valid';
        } else {
            return 'invalid';
        }
    }
}
