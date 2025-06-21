<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ShortLinkModel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ShortenLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.shorten-link.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shorten-link.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'direct_link' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'in:on,off|nullable',
        ]);

        try {
            $shortLinkModel = new ShortLinkModel();
            $shortLinkModel->name = $request->name;
            $shortLinkModel->direct_link = $request->direct_link;
            $shortLinkModel->description = $request->description;
            $shortLinkModel->is_active = $request->is_active === 'on' ? 1 : 0;
            $shortLinkModel->created_by = auth()->user()->id;

            // Generate unique code
            $shortLinkModel->code = $this->generateUniqueCode(10);

            if ($shortLinkModel->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Short link created successfully',
                    'data' => $shortLinkModel,
                    'short_url' => url('/s/' . $shortLinkModel->code),
                ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Failed to create short link',
                        'errors' => 'error happen',
                    ],
                    422,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => 'error happens',
                ],
                500,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ShortLinkModel::findOrFail($id);

        return view('admin.shorten-link.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ShortLinkModel::findOrFail($id);

        return view('admin.shorten-link.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'direct_link' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'in:on,off|nullable',
        ]);

        try {
            $shortLinkModel = ShortLinkModel::findOrFail($id);
            $shortLinkModel->name = $request->name;
            $shortLinkModel->direct_link = $request->direct_link;
            $shortLinkModel->description = $request->description;
            $shortLinkModel->is_active = $request->is_active === 'on' ? 1 : 0;
            $shortLinkModel->updated_by = auth()->user()->id;

            if ($shortLinkModel->save()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Short link updated successfully',
                    'data' => $shortLinkModel,
                    'short_url' => url('/s/' . $shortLinkModel->code),
                ]);
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Failed to update short link',
                        'errors' => 'error happen',
                    ],
                    422,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => 'error happens',
                ],
                500,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $shortLink = ShortLinkModel::findOrFail($id);
            $shortLink->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Short link deleted successfully',
                ]);
            }

            return redirect()->route('adm.shorten-link.index')->with('success', 'Short link deleted successfully');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Failed to delete short link',
                    ],
                    500,
                );
            }

            return redirect()->back()->with('error', 'Failed to delete short link');
        }
    }

    /**
     * Generate a datatable for shorten link management page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shortenLinkDatatableAll(Request $request)
    {
        $query = ShortLinkModel::query();

        return DataTables::of($query)
            ->addColumn('action', function ($shortLink) {
                $editUrl = '<a href="' . route('adm.shorten-link.edit', $shortLink->id) . '" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>';

                $deleteUrl = '<form class="d-inline delete-form" action="' . route('adm.shorten-link.destroy', $shortLink->id) . '" method="POST">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-xs delete-btn" title="Delete"><i class="fas fa-trash"></i></button></form>';

                $shortUrl = '<a href="' . url('/s/' . $shortLink->code) . '" target="_blank" class="btn btn-primary btn-xs" title="Show"><i class="fas fa-external-link-alt"></i></a>';

                return $editUrl . ' ' . $shortUrl . ' ' . $deleteUrl;
            })
            ->editColumn('is_active', function ($shortLink) {
                return $shortLink->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('direct_link', function ($link) {
                return '<a href="' . $link->direct_link . '" target="_blank">' . Str::limit($link->direct_link, 30) . '</a>';
            })
            ->addColumn('short_url_column', function ($shortLink) {
                $shortUrl = url('/s/' . $shortLink->code);
                return '
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm short-url-input" value="' .
                    $shortUrl .
                    '" readonly>
                    <button class="btn btn-outline-secondary copy-short-url" type="button" data-url="' .
                    $shortUrl .
                    '">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>';
            })
            ->rawColumns(['action', 'is_active', 'direct_link', 'short_url_column'])
            ->make(true);
    }

    /**
     * Generate a unique code for short URL
     *
     * @param int $length Panjang kode (default: 10)
     * @return string Kode unik
     * @throws \Exception Jika gagal menghasilkan kode unik setelah beberapa percobaan
     */
    protected function generateUniqueCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $code = '';
            // Generate random code
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            // Check if code already exists in database
            $exists = ShortLinkModel::where('code', $code)->exists();

            $attempt++;

            if ($attempt >= $maxAttempts) {
                throw new \Exception('Failed to generate unique code after ' . $maxAttempts . ' attempts');
            }
        } while ($exists); // Ulangi jika kode sudah ada

        return $code;
    }
}
