<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use Yajra\DataTables\DataTables;

use App\Models\Organization;
use Exception;

class OrganizationController extends Controller
{
    protected $organizationColumn = ['id', 'uuid', 'name', 'phone', 'email', 'address', 'alias_names', 'created_at', 'updated_at'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization_total_payout = \App\Models\Organization::withSum(
            [
                'program as total_nominal_payout' => function ($query) {
                    $query->join('payout', 'payout.program_id', '=', 'program.id')->where('payout.status', 'paid'); // kalau hanya mau yang status paid
                },
            ],
            'payout.nominal_approved',
        )
            ->orderBy('id', 'asc') // ambil organisasi pertama
            ->first();

        $organization_total_pengeluaran_ads = \App\Models\Organization::withSum(
            [
                'program as total_ads_nominal' => function ($query) {
                    $query->join('ads_campaign', 'ads_campaign.program_id', '=', 'program.id'); // kalau hanya mau yang status paid
                },
            ],
            'ads_campaign.spend',
        )
            ->orderBy('id', 'asc') // ambil organisasi pertama
            ->find(2);

        $organization_total_donation = \App\Models\Organization::withSum(
            [
                'program as total_donation_nominal' => function ($query) {
                    $query->join('transaction', 'transaction.program_id', '=', 'program.id'); // kalau hanya mau yang status paid
                },
            ],
            'transaction.nominal_final',
        )
            ->orderBy('id', 'asc') // ambil organisasi pertama
            ->latest()
            ->first();

        $organization_dss = $organization_total_donation->total_donation_nominal - $organization_total_pengeluaran_ads->total_ads_nominal;

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
            'name' => 'required|unique:organization,name',
            'status' => 'required|in:verified,verif_org,banned',
            'phone' => 'required|max:20',
            'mail' => 'required|email',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            $data = new Organization();
            $data->name = $request->name;
            $data->uuid = date('ymdhis');
            $data->phone = $request->phone;
            $data->email = $request->mail;
            $data->password = '-';
            $data->address = $request->address ?? 'Indonesia';
            $data->about = $request->about;
            $data->status = $request->status;
            $data->pic_fullname = $request->pic_name;
            $data->pic_nik = $request->pic_nik;
            $data->created_by = Auth::user()->id;

            $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
            $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename_logo = 'logo_' . $filename . '.jpg';

                // Process image with Intervention Image
                $image = Image::make($file->getRealPath())
                    ->fit(50, 50) // Resize to 50x50
                    ->encode('jpg', 80);

                // Path for storage
                $path = 'images/fundraiser/' . $filename_logo;

                // Save using Storage
                Storage::disk('public_uploads')->put($path, $image->stream());

                $data->logo = $filename_logo;
            }

            if ($request->hasFile('pic_image')) {
                $filet = $request->file('pic_image');
                $filename_vr = 'verified_' . $filename . '.jpg';

                // Process image with Intervention Image
                $image = Image::make($filet->getRealPath())->encode('jpg', 80);

                // Path for storage
                $path = 'images/fundraiser/' . $filename_vr;

                // Save using Storage
                Storage::disk('public_uploads')->put($path, $image->stream());

                $data->pic_image = $filename_vr;
            }

            $data->save();

            return redirect()->route('adm.organization.index')->with('success', 'Berhasil tambah data lembaga');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal tambah, ada kesalahan teknis: ' . $e->getMessage());
        }
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
            'name' => 'required|string|max:255',
            'status' => 'required|in:verified,verif_org,banned',
            'phone' => 'required|max:20',
            'mail' => 'required|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about' => 'required|string',
            'address' => 'required|string',
        ]);

        try {
            $data = Organization::findOrFail($id);

            if ($request->filled('link') && $request->link != $data->uuid) {
                $check_uuid = Organization::select('uuid')
                    ->where('uuid', trim($request->link))
                    ->where('id', '!=', $id)
                    ->first();
                if (isset($check_uuid->uuid)) {
                    return redirect()->back()->with('error', 'Gagal update, duplikat LINK');
                }
                $data->uuid = trim($request->link);
            }

            $data->name = $request->name;
            $data->phone = $request->phone;
            $data->email = $request->mail;
            $data->address = $request->address ?? 'Indonesia';
            $data->about = $request->about;
            $data->status = $request->status;
            $data->pic_fullname = $request->pic_name;
            $data->pic_nik = $request->pic_nik;
            $data->updated_by = Auth::user()->id;

            $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
            $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($data->logo) {
                    $oldPath = 'images/fundraiser/' . $data->logo;
                    if (Storage::disk('public_uploads')->exists($oldPath)) {
                        Storage::disk('public_uploads')->delete($oldPath);
                    }
                }

                $file = $request->file('logo');
                $filename_logo = 'logo_' . $filename . '.jpg';

                // Process image with Intervention Image
                $image = Image::make($file->getRealPath())
                    ->fit(50, 50) // Resize to 50x50
                    ->encode('jpg', 80);

                // Path for storage
                $path = 'images/fundraiser/' . $filename_logo;

                // Save using Storage
                Storage::disk('public_uploads')->put($path, $image->stream());

                $data->logo = $filename_logo;
            }

            if ($request->hasFile('pic_image')) {
                // Delete old pic_image if exists
                if ($data->pic_image) {
                    $oldPath = 'images/fundraiser/' . $data->pic_image;
                    if (Storage::disk('public_uploads')->exists($oldPath)) {
                        Storage::disk('public_uploads')->delete($oldPath);
                    }
                }

                $filet = $request->file('pic_image');
                $filename_vr = 'verified_' . $filename . '.jpg';

                // Process image with Intervention Image
                $image = Image::make($filet->getRealPath())->encode('jpg', 80);

                // Path for storage
                $path = 'images/fundraiser/' . $filename_vr;

                // Save using Storage
                Storage::disk('public_uploads')->put($path, $image->stream());

                $data->pic_image = $filename_vr;
            }

            $data->save();

            return redirect()->back()->with('success', 'Berhasil update data lembaga');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update, ada kesalahan teknis: ' . $e->getMessage());
        }
    }

    /**
     * Datatables Donatur
     */
    public function orgDatatables(Request $request)
    {
        $cacheKey = 'org_list';

        if ($request->has('refresh')) {
            Cache::forget($cacheKey);
        }
        
        $cacheTTL = now()->addDays(3);

        $data = Cache::remember($cacheKey, $cacheTTL, function () {
            return Organization::select($this->organizationColumn)
                // total payout // mengambil dari payout.nominal_approved
                ->withSum(
                    [
                        'program as total_nominal_payout' => function ($query) {
                            $query->join('payout', 'payout.program_id', '=', 'program.id')->where('payout.status', 'paid');
                        },
                    ],
                    'payout.nominal_approved',
                )
                // total ads // mengambil dari ads_campaign.spend
                ->withSum(
                    [
                        'program as total_ads_nominal' => function ($query) {
                            $query->join('ads_campaign', 'ads_campaign.program_id', '=', 'program.id');
                        },
                    ],
                    'ads_campaign.spend',
                )
                // total donate paid // mengambil dari transaction.nominal_final
                ->withSum(
                    [
                        'program as total_donation_nominal' => function ($query) {
                            $query->join('transaction', 'transaction.program_id', '=', 'program.id')->where('transaction.status', 'success');
                        },
                    ],
                    'transaction.nominal_final',
                )
                // total donate count // mengambil dari transaction
                ->withCount(
                    [
                        'program as total_donation_count' => function ($query) {
                            $query->join('transaction', 'transaction.program_id', '=', 'program.id')->where('transaction.status', 'success');
                        },
                    ]
                )
                ->orderBy('name', 'DESC')
                ->get();
        });

        // Filter manual sesuai request
        $data = $data->filter(function ($item) use ($request) {
            return (!$request->filled('name') || str_contains(strtolower($item->name), strtolower(urldecode($request->name)))) && (!$request->filled('phone') || str_contains(strtolower($item->phone), strtolower(urldecode($request->phone)))) && (!$request->filled('email') || str_contains(strtolower($item->email), strtolower(urldecode($request->email)))) && (!$request->filled('about') || str_contains(strtolower($item->about), strtolower(urldecode($request->about)))) && (!$request->filled('status') || str_contains(strtolower($item->status), strtolower(urldecode($request->status))));
        });

        // Search global
        $search = $request->input('search.value');
        if ($search) {
            $data = $data->filter(function ($item) use ($search) {
                $search = strtolower($search);
                return str_contains(strtolower($item->name), $search) || str_contains(strtolower($item->phone), $search) || str_contains(strtolower($item->email), $search) || str_contains(strtolower($item->address), $search) || str_contains(strtolower($item->status), $search) || str_contains(strtolower($item->about), $search);
            });
        }

        $count_total = Cache::get($cacheKey)->count();
        $count_filter = $data->count();

        $pageSize = $request->input('length', 10);
        $start = $request->input('start', 0);
        $pagedData = $data->slice($start, $pageSize)->values();

        return Datatables::of($pagedData)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                if ($row->status == 'verified') {
                    $status = '<span class="badge badge-sm badge-success" title="Terverifikasi sebagai perorangan"><i class="fa fa-check"></i> Personal</span>';
                } elseif ($row->status == 'verif_org') {
                    $status = '<span class="badge badge-sm badge-success" title="Terverifikasi sebagai lembaga"><i class="fa fa-check"></i> Lembaga</span>';
                } elseif ($row->status == 'banned') {
                    $status = '<span class="badge badge-sm badge-danger" title="Diblokir"><i class="fa fa-times"></i> Banned</span>';
                } else {
                    $status = '<span class="badge badge-sm badge-info" title="Belum terverifikasi"><i class="fa fa-question"></i> Belum</span>';
                }
                return ucwords($row->name) . '<br>' . $status;
            })
            ->addColumn('contact', function ($row) {
                return $row->phone . '<br>' . $row->email;
            })
            ->addColumn('summary', function ($row) {
                $count_program = \App\Models\Program::where('organization_id', $row->id)->count('id');
                $sum_donate_paid = \App\Models\Transaction::join('program', 'program.id', '=', 'program_id')->where('organization_id', $row->id)->where('transaction.status', 'success')->sum('nominal_final');
                return number_format($count_program, 0, ',', '.') . ' Program <br>Rp. ' . number_format($sum_donate_paid, 0, ',', '.');
            })
            ->addColumn('finance', function ($row) {
                $total_donation_nominal = $row->total_donation_nominal ?? 0;
                $total_donation_count = $row->total_donation_count ?? 0;
                $total_pengeluaran = $row->total_ads_nominal ?? 0;
                $total_penyaluran = $row->total_nominal_payout ?? 0;

                $donasi_show = number_format($total_donation_nominal, 0, ',', '.');
                $donasi_qty_show = number_format($total_donation_count, 0, ',', '.');
                $pengeluaran_show = number_format($total_pengeluaran, 0, ',', '.');
                $penyaluran_show = number_format($total_penyaluran, 0, ',', '.');

                return '<span class="badge badge-light" title="Total Donasi">
                            <i class="fa fa-hand-holding-heart icon-gradient bg-happy-green"></i> Rp.' . $donasi_show . ' (' . $donasi_qty_show . ' qty)
                        </span><br>
                        <span class="badge badge-light" title="Total Pengeluaran ADS">
                            <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.' . $pengeluaran_show . '
                        </span><br>
                        <span class="badge badge-light" title="Total Penyaluran">
                            <i class="fa fa-share icon-gradient bg-happy-green"></i> Rp.' . $penyaluran_show . '
                        </span>';
            })
            ->addColumn('dss', function ($row) {
                $total_donation = $row->total_donation_nominal ?? 0;
                $total_pengeluaran = $row->total_ads_nominal ?? 0;
                $dss = $total_donation - $total_pengeluaran;

                if ($dss >= 0) {
                    $badge_class = 'badge-success';
                    $icon = 'fa-arrow-up';
                } else {
                    $badge_class = 'badge-danger';
                    $icon = 'fa-arrow-down';
                }

                return '<span class="badge ' . $badge_class . '"><i class="fa ' . $icon . '"></i> Rp ' . number_format($dss, 0, ',', '.') . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn_edit = '<a href="' . route('adm.organization.edit', $row->id) . '" target="_blank" class="edit btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>';
                $btn_link = '<a href="' . route('campaigner', $row->uuid) . '" target="_blank" class="edit btn btn-info btn-xs" title="Link"><i class="fa fa-external-link-alt"></i></a>';
                return $btn_link . ' ' . $btn_edit;
            })
            ->addColumn('alias_names', function ($row) {
                $aliases = $row->alias_names ? json_decode($row->alias_names, true) : [];
                $alias_names = !empty($aliases) ? collect($aliases)->map(fn($item) => '<span class="badge badge-sm badge-info">' . htmlspecialchars($item) . '</span>')->implode(' ') : '<span class="badge badge-sm badge-secondary">belum ada alias</span>';

                $alias_names_js = $row->alias_names ?: '[]';

                return $alias_names .
                    ' <a href="#" class="edit-icon" title="Edit" style="cursor: pointer;"
                onClick=\'openAddAliasModal(' .
                    $row->id .
                    ', "' .
                    addslashes($row->name) .
                    '", ' .
                    $alias_names_js .
                    ')\'>
                <i class="fa fa-edit"></i></a>';
            })
            ->rawColumns(['name', 'contact', 'summary', 'finance', 'dss', 'action', 'alias_names'])
            ->make(true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function select2(Request $request)
    {
        $query = Organization::query();
        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            $query = $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 10);
        $paginator = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $paginator->items(),
            'last_page' => $paginator->lastPage(),
            'total' => $paginator->total(),
            'extra_data' => [
                'last_page' => $last_page,
            ],
        ]);
    }

    public function newOrgAlias(Request $request)
    {
        $validated = $request->validate(
            [
                'id_organization' => 'required|exists:organization,id',
                'aliases_array' => 'required|json',
            ],
            [
                'id_organization.exists' => 'Data Organization tidak ditemukan',
                'aliases_array.json' => 'Data Nama Alias harus berupa JSON',
                'aliases_array.required' => 'Data Nama Alias harus diisi',
            ],
        );

        $organization = Organization::find($request->id_organization);

        try {
            $organization->alias_names = $request->aliases_array;

            if ($organization->save()) {
                Cache::delete('org_list');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Nama alias berhasil disimpan',
            ]);
        } catch (Exception $err) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nama alias gagal ditambahkan',
            ]);
        }
    }
}
