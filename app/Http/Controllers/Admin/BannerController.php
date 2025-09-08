<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        return view('admin.banner.index');
    }

    /**
     * Datatables for banners.
     */
    public function bannerDatatables(Request $request)
    {
        $data = Banner::select('id', 'title', 'url', 'is_publish', 'created_at', 'image', 'type')->latest();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('links', function ($row) {
                $imageUrl = asset($row->image);
                $targetUrl = $row->url;

                $imageBtn = '<a href="' . $imageUrl . '" target="_blank" class="btn btn-info btn-xs" title="Gambar"><i class="fa fa-image"></i> gambar</a>';
                $linkBtn = '<a href="' . $targetUrl . '" target="_blank" class="btn btn-secondary btn-xs" title="URL"><i class="fa fa-link"></i> link</a>';

                return $imageBtn . ' ' . $linkBtn;
            })
            ->addColumn('action', function ($row) {
                $url_show = route('adm.banner.show', $row->id);
                $url_edit = route('adm.banner.edit', $row->id);
                $url_delete = route('adm.banner.destroy', $row->id);
                $actionBtn =
                    '<a href="' . $url_show . '" class="btn btn-info btn-xs mb-1" title="Show"><i class="fa fa-eye"></i></a>
                    <a href="' . $url_edit . '" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
                    <form action="' . $url_delete . '" method="POST" class="d-inline" id="delete-form-'.$row->id.'">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" class="btn btn-danger btn-xs mb-1 delete-btn" data-id="'.$row->id.'" title="Delete"><i class="fa fa-trash"></i></button>
                    </form>';
                return $actionBtn;
            })
            ->editColumn('is_publish', function ($row) {
                $status = $row->is_publish ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-danger">Draft</span>';
                return '<a href="#" class="change-status-btn" data-id="' . $row->id . '" title="Klik untuk ubah status">' . $status . '</a>';
            })
            ->addColumn('type', function ($row) {
                if ($row->type === 'banner') {
                    return '<span class="badge bg-primary">Banner</span>';
                } elseif ($row->type === 'popup') {
                    return '<span class="badge bg-secondary">Popup</span>';
                }
                return '-';
            })
            ->rawColumns(['action', 'is_publish', 'links', 'type'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|url',
            'alt' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_publish' => 'required|boolean',
            'description' => 'nullable|string',
            'type' => 'required|string|in:banner,popup',
            'is_forever' => 'nullable|boolean',
            'expire_date' => $request->is_forever ? 'nullable|date' : 'required|date',
        ]);

        try {
            $file = $request->file('image');
            $fileName = Str::slug($request->title) . '_' . time() . '.jpg';
            $path = 'images/' . $request->type . '/' . $fileName;

            $image = Image::make($file->getRealPath());

            if ($request->type === 'popup') {
                $image->fit(320, 320, function ($constraint) {
                    $constraint->upsize();
                });
            } else {
                $image->fit(580, 280, function ($constraint) {
                    $constraint->upsize();
                });
            }

            $image->encode('jpg', 85);

            Storage::disk('public_uploads')->put($path, $image->stream());

            $imageAlt = $request->alt ?? Str::slug('title');

            Banner::create([
                'title' => $request->title,
                'url' => $request->url,
                'image' => 'public/' . $path,
                'alt' => $imageAlt,
                'is_publish' => $request->is_publish,
                'description' => $request->description,
                'created_by' => auth()->user()->id,
                'type' => $request->type,
                'is_forever' => $request->boolean('is_forever'),
                'expire_date' => $request->boolean('is_forever') ? null : $request->expire_date,
            ]);

            return redirect()->route('adm.banner.index')->with('message', ['type' => 'success', 'text' => 'Data berhasil ditambahkan.']);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('message', ['type' => 'error', 'text' => 'Data gagal ditambahkan.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alt' => 'nullable|string|max:255',
            'is_publish' => 'required|boolean',
            'description' => 'nullable|string',
            'type' => 'required|string|in:banner,popup',
            'is_forever' => 'nullable|boolean',
            'expire_date' => $request->is_forever ? 'nullable|date' : 'required|date',
        ]);

        try {
            $imagePath = $banner->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($banner->image && Storage::disk('public_uploads')->exists($banner->image)) {
                    Storage::disk('public_uploads')->delete($banner->image);
                }
                
                // Store new image
                $file = $request->file('image');
                $fileName = Str::slug($request->title) . '_' . time() . '.jpg';
                $path = 'images/' . $request->type . '/' . $fileName;
    
                $image = Image::make($file->getRealPath());

                if ($request->type === 'popup') {
                    $image->fit(320, 320, function ($constraint) {
                        $constraint->upsize();
                    });
                } else {
                    $image->fit(580, 280, function ($constraint) {
                        $constraint->upsize();
                    });
                }

                $image->encode('jpg', 85);
    
                Storage::disk('public_uploads')->put($path, $image->stream());
                $imagePath = 'public/' . $path;
            }

            $imageAlt = $request->alt ?? Str::slug('title');

            $banner->update([
                'title' => $request->title,
                'url' => $request->url,
                'alt' => $imageAlt,
                'image' => $imagePath,
                'is_publish' => $request->is_publish,
                'description' => $request->description,
                'type' => $request->type,
                'is_forever' => $request->boolean('is_forever'),
                'expire_date' => $request->boolean('is_forever') ? null : $request->expire_date,
            ]);

            return redirect()->route('adm.banner.index')->with('message', ['type' => 'success', 'text' => 'Data berhasil diupdate.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('message', ['type' => 'error', 'text' => 'Data gagal diupdate.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            // Delete image
            if ($banner->image && Storage::disk('public_uploads')->exists($banner->image)) {
                Storage::disk('public_uploads')->delete($banner->image);
            }
            $banner->delete();
            return redirect()->route('adm.banner.index')->with('message', ['type' => 'success', 'text' => 'Banner berhasil dihapus.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('message', ['type' => 'error', 'text' => 'Banner gagal dihapus.']);
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:banners,id']);

        try {
            $banner = Banner::findOrFail($request->id);
            $banner->update(['is_publish' => !$banner->is_publish]);
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status.'], 500);
        }
    }
}