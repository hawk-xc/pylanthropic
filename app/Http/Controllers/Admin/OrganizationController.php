<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

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
            ->orderBy('id', 'asc')
            ->first();

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
                $filename_logo = 'logo_' . $filename . '_' . time() . '.jpg';
                $destinationPath = public_path('images/fundraiser');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                Image::make($file)->fit(50, 50)->save($destinationPath . '/' . $filename_logo, 80);
                $data->logo = $filename_logo;
            }

            if ($request->hasFile('pic_image')) {
                $filet = $request->file('pic_image');
                $filename_vr = 'verified_' . $filename . '_' . time() . '.jpg';
                $destinationPath = public_path('images/fundraiser');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                Image::make($filet)->save($destinationPath . '/' . $filename_vr, 80);
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
                $destinationPath = public_path('images/fundraiser');

                // Delete old logo if exists
                if ($data->logo && file_exists($destinationPath . '/' . $data->logo)) {
                    unlink($destinationPath . '/' . $data->logo);
                }

                $file = $request->file('logo');
                $filename_logo = 'logo_' . $filename . '_' . time() . '.jpg';

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                Image::make($file)->fit(50, 50)->save($destinationPath . '/' . $filename_logo, 80);
                $data->logo = $filename_logo;
            }

            if ($request->hasFile('pic_image')) {
                $destinationPath = public_path('images/fundraiser');
                // Delete old pic_image if exists
                if ($data->pic_image && file_exists($destinationPath . '/' . $data->pic_image)) {
                    unlink($destinationPath . '/' . $data->pic_image);
                }

                $filet = $request->file('pic_image');
                $filename_vr = 'verified_' . $filename . '_' . time() . '.jpg';

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                Image::make($filet)->save($destinationPath . '/' . $filename_vr, 80);
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
        $cacheKey = 'org_list.' . md5(json_encode($request->all()));

        if ($request->has('refresh')) {
            Cache::forget($cacheKey);
        }

        $cacheTTL = now()->addDays(3);

        return Cache::remember($cacheKey, $cacheTTL, function () use ($request) {
            $query = Organization::query()
                ->select('organization.*')
                // total payout // mengambil dari payout.nominal_approved
                ->addSelect(
                    [
                        'total_nominal_payout' => \App\Models\Program::query()
                            ->selectRaw('COALESCE(SUM(payout.nominal_approved), 0)')
                            ->join('payout', 'payout.program_id', '=', 'program.id')
                            ->whereColumn('organization_id', 'organization.id')
                            ->where('payout.status', 'paid'),
                    ]
                )
                // total ads // mengambil dari ads_campaign.spend
                ->addSelect(
                    [
                        'total_ads_nominal' => \App\Models\Program::query()
                            ->selectRaw('COALESCE(SUM(ads_campaign.spend), 0)')
                            ->join('ads_campaign', 'ads_campaign.program_id', '=', 'program.id')
                            ->whereColumn('organization_id', 'organization.id'),
                    ]
                )
                // total donate paid // mengambil dari transaction.nominal_final
                ->addSelect(
                    [
                        'total_donation_nominal' => \App\Models\Program::query()
                            ->selectRaw('COALESCE(SUM(transaction.nominal_final), 0)')
                            ->join('transaction', 'transaction.program_id', '=', 'program.id')
                            ->whereColumn('organization_id', 'organization.id')
                            ->where('transaction.status', 'success'),
                    ]
                )
                // total donate count // mengambil dari transaction
                ->addSelect(
                    [
                        'total_donation_count' => \App\Models\Program::query()
                            ->selectRaw('COALESCE(COUNT(transaction.id), 0)')
                            ->join('transaction', 'transaction.program_id', '=', 'program.id')
                            ->whereColumn('organization_id', 'organization.id')
                            ->where('transaction.status', 'success'),
                    ]
                )
                // DSS
                ->addSelect(DB::raw('(
                    (SELECT COALESCE(SUM(t.nominal_final), 0) 
                     FROM transaction t 
                     JOIN program p ON t.program_id = p.id 
                     WHERE p.organization_id = organization.id AND t.status = \'success\') 
                    - 
                    (SELECT COALESCE(SUM(ac.spend), 0) 
                     FROM ads_campaign ac 
                     JOIN program p2 ON ac.program_id = p2.id 
                     WHERE p2.organization_id = organization.id)
                ) as dss'));


            // Add status filters here to the query
            $query->when($request->query('status_filter') === 'regular', fn($q) =>
                $q->where('status', 'regular')
            )->when($request->query('status_filter') === 'verified', fn($q) =>
                $q->where('status', 'verified')
            )->when($request->query('status_filter') === 'banned', fn($q) =>
                $q->where('status', 'banned')
            )->when($request->query('status_filter') === 'verif_org', fn($q) =>
                $q->where('status', 'verif_org')
            );

            // Text filters
            $query->when($request->filled('name'), fn($q) =>
                $q->where('organization.name', 'like', '%' . urldecode($request->name) . '%')
            )->when($request->filled('phone'), fn($q) =>
                $q->where('organization.phone', 'like', '%' . urldecode($request->phone) . '%')
            )->when($request->filled('email'), fn($q) =>
                $q->where('organization.email', 'like', '%' . urldecode($request->email) . '%')
            );

            // Urutkan/sort
            $sort = $request->input('sort');
            $dir  = strtolower($request->input('dir','desc'));
            $allowed = ['total_donation_nominal', 'total_ads_nominal', 'total_nominal_payout', 'dss'];

            if (in_array($sort, $allowed, true)) {
                $dir = $dir === 'asc' ? 'asc' : 'desc';
                $query->orderBy($sort, $dir);
            } else {
                $query->orderBy('name', 'ASC');
            }

            return DataTables::of($query)
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

                return '<span class="badge ' . $badge_class . '"><i class="fa ' . $icon . '"></i> Rp ' . number_format($dss, 0, ",", ".") . '</span>';
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
            ->filter(function ($query) use ($request) {
                    $search = data_get($request->input('search'), 'value');
                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('organization.name', 'like', "%{$search}%")
                            ->orWhere('organization.phone', 'like', "%{$search}%")
                            ->orWhere('organization.email', 'like', "%{$search}%")
                            ->orWhere('organization.address', 'like', "%{$search}%");
                        });
                    }
                }, true)
            ->make(true);
        });
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
