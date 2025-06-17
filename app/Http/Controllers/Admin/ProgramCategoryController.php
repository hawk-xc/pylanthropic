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
        $occupiedPositions = ProgramCategory::pluck('sort_number')->toArray();

        return view('admin.program-category.create', [
            'occupiedPositions' => $occupiedPositions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string|unique:program_category,slug',
            'logo_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'sort_number' => 'required|integer|unique:program_category,sort_number'
        ], [
            'title.required' => 'Judul harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'url.required' => 'URL harus diisi.',
            'url.string' => 'URL harus berupa teks.',
            'url.unique' => 'URL sudah digunakan.',
            'logo_image.image' => 'Logo harus berupa gambar.',
            'logo_image.mimes' => 'Logo harus berupa file bertipe: jpeg, png, jpg, gif, svg.',
            'logo_image.max' => 'Logo tidak boleh lebih besar dari 2048 kilobytes.',
            'sort_number.required' => 'Nomor urut harus diisi.',
            'sort_number.integer' => 'Nomor urut harus berupa angka.',
            'sort_number.unique' => 'Nomor urut sudah digunakan.',
        ]);


        try {
            $programCategory = new ProgramCategory();
            $programCategory->name = $request->title;
            $programCategory->slug = Str::slug($request->url);
            $programCategory->sort_number = $request->sort_number;
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

        $pivotRecords = $category->programCategories()
            ->with('program:id,title,donate_sum')
            ->get();

            $data = [];

        $programs = $pivotRecords->map(function($pivotRecord) {
            $data[] = $pivotRecord;
            return $pivotRecord->program;
        });

        $totalDonations = $programs->sum('donate_sum');

        return view('admin.program-category.show', [
            'category' => $category,
            'totalPrograms' => $programs,
            'totalDonations' => $totalDonations
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = ProgramCategory::findOrFail($id);
        $occupiedPositions = ProgramCategory::pluck('sort_number')->toArray();

        return view('admin.program-category.edit', [
            'category' => $category,
            'occupiedPositions' => $occupiedPositions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string|unique:program_category,slug,' . $id,
            'logo_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'sort_number' => 'unique:program_category,sort_number'
        ], [
            'title.required' => 'Judul harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'url.required' => 'URL harus diisi.',
            'url.string' => 'URL harus berupa teks.',
            'url.unique' => 'URL sudah digunakan.',
            'logo_image.image' => 'Logo harus berupa gambar.',
            'logo_image.mimes' => 'Logo harus berupa file bertipe: jpeg, png, jpg, gif, svg.',
            'logo_image.max' => 'Logo tidak boleh lebih besar dari 2048 kilobytes.',
            'sort_number.unique' => 'Nomor urut sudah digunakan.',
        ]);

        try {
            $programCategory = ProgramCategory::findOrFail($id);
            $programCategory->name = $request->title;
            $programCategory->slug = Str::slug($request->url);
            $programCategory->is_show = $request->has('is_show') ? 1 : 0;

            if ($request->sort_number !== null) {
                $programCategory->sort_number = $request->sort_number;
            }

            if ($request->hasFile('logo_image')) {
                $logoImage = $request->file('logo_image');
                $logoImageName = time() . '.' . $logoImage->getClientOriginalExtension();

                $destinationPath = public_path('public/images/categories');

                if ($programCategory->icon && file_exists($destinationPath . '/' . $programCategory->icon)) {
                    unlink($destinationPath . '/' . $programCategory->icon);
                }

                $logoImage->move($destinationPath, $logoImageName);

                $programCategory->icon = $logoImageName;
            }

            $programCategory->save();

            return redirect()->route('adm.program-category.index')->with('success', 'Program Category berhasil diperbarui.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('adm.program-category.index')->with('error', 'Terjadi kesalahan saat memperbarui Program Category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ProgramCategory::find($id);

        if ($category) {
            $category->delete();
            return redirect()->route('adm.program-category.index')->with('success', 'Program Category berhasil dihapus.');
        } else {
            return redirect()->route('adm.program-category.index')->with('error', 'Program Category tidak ditemukan.');
        }
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

    /**
     * Display all data from resource.
     */
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

    public function datatableProgramCategoryDetail(Request $request)
    {
        $category_id = $request->category_id;

        $category = ProgramCategory::findOrFail($category_id);

        // Get pivot records with programs and organization
        $query = $category->programCategories()
            ->with(['program' => function($query) {
                $query->select('id', 'title', 'slug', 'organization_id', 'donate_sum')
                      ->with('programOrganization:id,name');
            }]);

        return DataTables::of($query)
            ->addColumn('title', function($pivot) {
                return $pivot->program->title;
            })
            ->addColumn('organization', function($pivot) {
                return $pivot->program->programOrganization->name ?? '-';
            })
            ->addColumn('donate', function($pivot) {
                return 'Rp ' . number_format((float)$pivot->program->donate_sum, 0, ',', '.');
            })
            ->addColumn('action', function($pivot) {
                return '<a href="'.route('program.index', $pivot->program->slug).'" class="edit btn btn-info btn-xs mb-1" title="Link" target="_blank"><i class="fa fa-external-link-alt"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
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
