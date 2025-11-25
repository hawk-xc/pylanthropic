<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Payout;
use App\Models\Program;
use App\Models\AdsCampaign;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\ProgramSpend;
// use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

use App\Models\TrackingVisitor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program.create');
    }


    public function storeImagecontent(Request $request)
    {
        $file = $request->file('file');
        $number = $request->number;
        $number = str_replace('img', '', $number);
        $filename = time() . '_' . $number . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path('public/images/program/content');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        $link_img = asset('public/images/program/content/' . $filename);

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
            $destinationPath = public_path('public/images/program/content');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);

            $url = asset('public/images/program/content/' . $filename);

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

    /**
     * Store image content
     */
    public function checkUrl(Request $request)
    {
        $url = $request->url;
        $url = str_replace([' ', "'", '"', ',', ';', ':', '&'], '', $url);
        $url = preg_replace('/[^A-Za-z0-9\_-]/', '', $url);

        $cek = Program::where('slug', $url)
            ->where('is_publish', 1)
            ->where('end_date', '>', date('Y-m-d') . ' 23:59:59')
            ->select('id')
            ->count();

        if ($cek < 1) {
            return 'valid';
        } else {
            return 'invalid';
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $forever_checked = $request->has('forever_checked') == 'on';

        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'category' => 'required',
            'organization' => 'required|numeric',
            'nominal' => 'required',
            'show' => 'required',
            'img_primary' => 'required|file|max:1024',
            'caption' => 'required',
            'story' => 'required',
        ]);

        if ($forever_checked) {
            $request->validate([
                'date_end' => 'nullable',
            ]);
        } else {
            $request->validate([
                'date_end' => 'required|date',
            ]);
        }

        if (!$request->has('same_as_thumbnail')) {
            $request->validate([
                'thumbnail' => 'required|file|max:600',
            ]);
        }

        try {
            $data = new Program();
            $data->title = $request->title;
            $data->slug = urlencode($request->url);
            $data->organization_id = $request->organization;
            $data->nominal_request = str_replace('.', '', $request->nominal);
            $data->nominal_approved = str_replace('.', '', $request->nominal);
            $data->end_date = $request->date_end ?? null;
            $data->short_desc = $request->caption;
            $data->is_islami = $request->is_islami ? 1 : 0;

            $story = str_replace('&lt;', '<', $request->story);
            $story = str_replace('&gt;', '>', $story);
            $story = str_replace('Bantusesama.com" /></p>', 'Bantusesama.com" />', $story);
            $story = str_replace('<p><img', '<img', $story);
            $data->about = $story . '<img class="lazyload" data-original="https://bantubersama.com/public/images/program/cara_donasi.webp" alt="Cara Berdonasi di Bantusesama.com" />';

            // Handle show options
            if ($request->show == 1) {
                $data->is_publish = 1;
                $data->is_recommended = 0;
                $data->is_show_home = 0;
            } elseif ($request->show == 2) {
                $data->is_publish = 1;
                $data->is_recommended = 1;
                $data->is_show_home = 0;
            } elseif ($request->show == 3) {
                $data->is_publish = 1;
                $data->is_recommended = 0;
                $data->is_show_home = 1;
            } elseif ($request->show == 4) {
                $data->is_publish = 0;
                $data->is_recommended = 0;
                $data->is_show_home = 0;
            }

            $timestamp = time();
            $destinationPath = public_path('public/images/program');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Upload image primary
            $filei = $request->file('img_primary');
            $imageName = $timestamp . '.' . $filei->getClientOriginalExtension();
            $filei->move($destinationPath, $imageName);
            $data->image = $imageName;

            // Handle thumbnail
            if ($request->has('same_as_thumbnail')) {
                // Since we can't re-process, we have to copy the file
                $thumbnailName = 'thumbnail_' . $imageName;
                File::copy($destinationPath . '/' . $imageName, $destinationPath . '/' . $thumbnailName);
                $data->same_as_thumbnail = true;
            } else {
                $filet = $request->file('thumbnail');
                $thumbnailName = 'thumbnail_' . $timestamp . '.' . $filet->getClientOriginalExtension();
                $filet->move($destinationPath, $thumbnailName);
                $data->same_as_thumbnail = false;
            }
            $data->thumbnail = $thumbnailName;

            $data->approved_at = date('Y-m-d H:i:s');
            $data->approved_by = 1;
            $data->created_by = 1;
            $data->save();
            $program_id = $data->id;

            // Insert program categories
            if (count($request->category) > 1) {
                foreach ($request->category as $category) {
                    $data_categories = new \App\Models\ProgramCategories();
                    $data_categories->program_id = $program_id;
                    $data_categories->program_category_id = $category;
                    $data_categories->is_active = 1;
                    $data_categories->save();
                }
            } else {
                $data_categories = new \App\Models\ProgramCategories();
                $data_categories->program_id = $program_id;
                $data_categories->program_category_id = $request->category[0];
                $data_categories->is_active = 1;
                $data_categories->save();
            }

            Cache::forget('program_public');

            return redirect(route('adm.program.index'))->with('success', 'Berhasil menambahkan program baru');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal update, ada kesalahan teknis: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = Program::select('program.*', 'organization.name')->join('organization', 'organization.id', 'organization_id')->where('program.id', $id)->first();

        return view('admin.program.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string',
                'url' => 'required|string',
                'organization' => 'required|numeric',
                'nominal' => 'required',
                'date_end' => 'required|date',
                'show' => 'required',
                'caption' => 'required',
                'img_primary' => 'file|max:800',
                'thumbnail' => 'file|max:400',
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'string' => 'Kolom :attribute harus berupa teks.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            ],
        );

        try {
            $data = Program::findOrFail($id);
            $data->title = $request->title;
            $data->slug = urlencode($request->url);
            $data->organization_id = $request->organization;
            $data->nominal_request = (int) str_replace('.', '', $request->nominal);
            $data->nominal_approved = (int) str_replace('.', '', $request->nominal);
            $data->end_date = $request->date_end;
            $data->short_desc = $request->caption;
            $data->is_islami = $request->is_islami ? 1 : 0;
            $data->optimation_fee = $request->optimation_fee;
            $data->show_minus = $request->show_minus;
            $data->same_as_thumbnail = $request->has('same_as_thumbnail');
            $data->about = $request->story;

            // Handle show options
            switch ($request->show) {
                case 1:  // Pencarian saja
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 2:  // Tampil di Pilihan
                    $data->is_publish = 1;
                    $data->is_recommended = 1;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 3:  // Tampil di Terbaru
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 1;
                    $data->is_urgent = 0;
                    break;
                case 4:  // Sembunyikan
                    $data->is_publish = 0;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 5:  // Mendesak
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 1;
                    break;
            }

            $timestamp = time();
            $destinationPath = public_path('public/images/program');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Handle primary image
            if ($request->hasFile('img_primary')) {
                // Delete old image
                if ($data->image && file_exists($destinationPath . '/' . $data->image)) {
                    unlink($destinationPath . '/' . $data->image);
                }

                $filei = $request->file('img_primary');
                $imageName = $timestamp . '.' . $filei->getClientOriginalExtension();
                $filei->move($destinationPath, $imageName);
                $data->image = $imageName;
            }

            // Handle thumbnail
            if ($request->has('same_as_thumbnail')) {
                // If a new thumbnail is being generated from a primary image (new or old)
                // we must first delete the old thumbnail
                if ($data->thumbnail && file_exists($destinationPath . '/' . $data->thumbnail)) {
                    unlink($destinationPath . '/' . $data->thumbnail);
                }
                
                // The source for the copy is always the current primary image
                $sourcePath = $destinationPath . '/' . $data->image;
                if (file_exists($sourcePath)) {
                    $thumbnailName = 'thumbnail_' . $data->image;
                    File::copy($sourcePath, $destinationPath . '/' . $thumbnailName);
                    $data->thumbnail = $thumbnailName;
                }
                $data->same_as_thumbnail = true;

            } elseif ($request->hasFile('thumbnail')) {
                // A specific thumbnail file is being uploaded
                if ($data->thumbnail && file_exists($destinationPath . '/' . $data->thumbnail)) {
                    unlink($destinationPath . '/' . $data->thumbnail);
                }

                $filet = $request->file('thumbnail');
                // Use a different timestamp if it's a different upload operation
                $thumbnailName = 'thumbnail_' . time() . '.' . $filet->getClientOriginalExtension();
                $filet->move($destinationPath, $thumbnailName);
                $data->thumbnail = $thumbnailName;
                $data->same_as_thumbnail = false;
            }

            $data->updated_by = Auth::user()->id;
            $data->updated_at = now();
            $data->save();

            Cache::forget('program_public');

            return redirect(route('adm.program.index'))->with('success', 'Berhasil mengubah program');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function datatablesProgram(Request $request)
    {
        $cacheKey = 'programs.datatables.' . md5(json_encode($request->all()));
        $cacheDuration = 60; // Cache for 60 minutes

        if ($request->has('refresh')) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, $cacheDuration, function () use ($request) {
            $today = Carbon::today()->toDateString();
            $d15ago = Carbon::today()->subDays(15)->toDateString();
            $d15ahead = Carbon::today()->addDays(15)->toDateString();

            // Base query + JOIN + subselect aggregates (no per-row queries)
            $data = Program::query()
                ->from('program')
                ->join('organization', 'organization.id', '=', 'program.organization_id')
                ->select([
                    'program.*',
                    'organization.name as organization',
                ])
                // Sum donasi sukses
                ->addSelect([
                    'donate_total' => Transaction::query()
                        ->selectRaw('COALESCE(SUM(nominal_final),0)')
                        ->whereColumn('program_id', 'program.id')
                        ->where('status', 'success'),
                ])
                // Sum pengeluaran (spend) done
                ->addSelect([
                    'spend_sum' => ProgramSpend::query()
                        ->selectRaw('COALESCE(SUM(nominal_approved),0)')
                        ->whereColumn('program_id', 'program.id')
                        ->where('status', 'done'),
                ])
                // Sum pengeluaran berdasarkan ads_campaign
                ->addSelect([
                    'spend_ads_campaign' => AdsCampaign::query()
                        ->selectRaw('COALESCE(SUM(spend),0)')
                        ->whereColumn('program_id', 'program.id'),
                ])
                // Sum penyaluran paid
                ->addSelect([
                    'payout_sum' => Payout::query()
                        ->selectRaw('COALESCE(SUM(nominal_approved),0)')
                        ->whereColumn('program_id', 'program.id')
                        ->where('status', 'paid'),
                ]);

            // Flag filters (pakai ->when biar clean)
            $data->when(
                $request->query('active') === '1',
                fn($q) =>
                $q->where('is_publish', 1)->where('end_date', '>=', $today)
            )->when(
                $request->query('inactive') === '1',
                fn($q) =>
                $q->where('is_publish', 0)
            )->when(
                $request->query('winning') === '1',
                fn($q) =>
                // pakai HAVING untuk alias aggregate
                $q->having('donate_total', '>=', 8000000)
            )->when(
                $request->query('recom') === '1',
                fn($q) =>
                $q->where('is_recommended', 1)
            )->when(
                $request->query('urgent') === '1',
                fn($q) =>
                $q->where('is_urgent', 1)
            )->when(
                $request->query('newest') === '1',
                fn($q) =>
                $q->where('is_show_home', 1)
            )->when($request->query('publish15day') === '1', function ($q) use ($d15ago) {
                return $q->where('program.approved_at', '>=', $d15ago);
            })->when($request->query('end15day') === '1', function ($q) use ($d15ahead) {
                return $q->where('end_date', '<=', $d15ahead);
            });

            // Optional filters (search by field)
            $data->when(
                $request->filled('program_title'),
                fn($q) =>
                $q->where('program.title', 'like', '%' . $request->program_title . '%')
            )->when(
                $request->filled('organization_name'),
                fn($q) =>
                $q->where('organization.name', 'like', '%' . $request->organization_name . '%')
            );

            // Urutkan/sort
            $sort = $request->input('sort');                  // e.g. payout_sum
            $dir  = strtolower($request->input('dir', 'desc')); // asc|desc
            $allowed = [
                'payout_sum',
                'donate_total',
                'spend_sum',
                'spend_ads_campaign',
                'approved_at',
                'end_date'
            ];
            if (in_array($sort, $allowed, true)) {
                $dir = $dir === 'asc' ? 'asc' : 'desc';
                $data->orderBy($sort, $dir);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nominal', function ($row) {
                    $sum = (float) $row->donate_total;
                    $spend = (float) $row->spend_sum;

                    $sum_percent = ($sum > 0 && $row->nominal_approved > 0) ? round(($sum / $row->nominal_approved) * 100, 2) : 0;
                    $spend_percent = ($spend > 0 && $sum > 0) ? round(($spend / $sum) * 100, 2) : 0;

                    $payout_sum_show = number_format($row->payout_sum, 0, ',', '.');
                    $payout_sum_percent = ($sum > 0 && $row->payout_sum > 0) ? round(($row->payout_sum / $sum) * 100, 2) : 0;

                    // amanin tanda kutip biar onclick gak pecah
                    $safeTitle = ucwords(str_replace("'", '', $row->title));
                    $param = $row->id . ", '" . e($safeTitle) . "'";

                    return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary(' . $param . ')">
                                <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.' . str_replace(',', '.', number_format($row->nominal_approved)) . '
                            </span><br>
                            <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate(' . $param . ')">
                                <i class="fa fa-hand-holding-heart icon-gradient bg-happy-green"></i> Rp.' . number_format($sum) . ' (' . $sum_percent . '%)
                            </span><br>
                            <span class="badge badge-light">
                                <i class="fa fa-share icon-gradient bg-happy-green"></i> Rp.' . $payout_sum_show . ' (' . $payout_sum_percent . '%)
                            </span>';
                })
                ->addColumn('status', function ($row) use ($today) {
                    if (!is_null($row->approved_at)) {
                        if ($row->end_date > $today && (string)$row->is_publish === '1') {
                            if ((int)$row->is_recommended === 1) {
                                $status = '<span class="badge badge-success">Tampil Dipilihan</span>';
                            } elseif ((int)$row->is_show_home === 1) {
                                $status = '<span class="badge badge-success">Tampil Diterbaru</span>';
                            } else {
                                $status = '<span class="badge badge-success">Tampil Dipencarian</span>';
                            }
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : 'selamanya');
                        } elseif ((string)$row->is_publish === '0') {
                            $status = '<span class="badge badge-danger">Tidak Tampil</span>';
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : '-');
                        } else {
                            $status = '<span class="badge badge-danger">Sudah Berakhir</span>';
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : '-');
                        }
                    } else {
                        $status = '<span class="badge badge-secondary">Belum Disetujui</span>';
                    }
                    return $status;
                })
                ->addColumn('donate', function ($row) { // ads_campaign
                    $sum = (float) $row->donate_total;
                    $dss_ads_campaign   = $sum - $row->spend_ads_campaign - $row->payout_sum - (0.15 * $sum) - (0.02 * $sum);

                    $spend_ads_campaign = number_format($row->spend_ads_campaign, 0, ',', '.');
                    $spend_ads_campaign_percent = ($sum > 0 && $row->spend_ads_campaign > 0) ? round(($sum / $row->spend_ads_campaign) * 100, 2) : 0;

                    $dss_ads_campaign_show   = number_format($dss_ads_campaign, 0, ',', '.');
                    $dss_ads_campaign_percent = ($sum > 0 && $dss_ads_campaign > 0) ? round(($sum / $dss_ads_campaign) * 100, 2) : 0;

                    return '<span class="badge badge-light">
                                <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.' . $spend_ads_campaign . ' (' . $spend_ads_campaign_percent . '%)
                            </span><br>
                            <span class="badge badge-light">
                                <i class="fa fa-heart icon-gradient bg-happy-green"></i> Rp.' . $dss_ads_campaign_show . ' (' . $dss_ads_campaign_percent . '%)
                            </span>';
                })
                ->addColumn('stats', function ($row) {  // spend
                    $sum = (float) $row->donate_total;
                    $spend = (float) $row->spend_sum;
                    $dss_spend = $sum - $spend - $row->payout_sum - (0.15 * $sum) - (0.02 * $sum);

                    $spend_show = number_format($spend, 0, ',', '.');
                    $spend_percent = ($spend > 0 && $sum > 0) ? round(($spend / $sum) * 100, 2) : 0;

                    $dss_spend_show    = number_format($dss_spend, 0, ',', '.');
                    $dss_spend_percent = ($sum > 0 && $dss_spend > 0) ? round(($sum / $dss_spend) * 100, 2) : 0;

                    // amanin tanda kutip biar onclick gak pecah
                    $safeTitle = ucwords(str_replace("'", '', $row->title));
                    $param = $row->id . ", '" . e($safeTitle) . "'";

                    return '<span class="badge badge-light" style="cursor:pointer" onclick="inpSpend(' . $param . ')">
                                <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.' . $spend_show . ' (' . $spend_percent . '%)
                            </span><br>
                            <span class="badge badge-light">
                                <i class="fa fa-heart icon-gradient bg-happy-green"></i> Rp.' . $dss_spend_show . ' (' . $dss_spend_percent . '%)
                            </span>';
                })
                ->addColumn('action', function ($row) {
                    $url_edit = route('adm.program.edit', $row->id);
                    return
                        '<a href="' . $url_edit . '" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
                        <a href="' . route('adm.program.detail.stats', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Statistik"><i class="fa fa-chart-line"></i></a>
                        <a href="' . route('adm.program.detail.fundraiser', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Donasi"><i class="fa fa-donate"></i></a>
                        <a href="' . route('adm.program.detail.donatur', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Donatur"><i class="fa fa-users"></i></a>
                        <a href="' . route('adm.program.detail.fundraiser', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Fundraiser"><i class="fa fa-people-carry"></i></a>
                        <a href="' . route('adm.program.detail.fundraiser', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Penyaluran"><i class="fa fa-hand-holding-heart"></i></a>
                        <a href="' . route('adm.program.detail.fundraiser', $row->id) . '" class="edit btn btn-info btn-xs mb-1" title="Operasional"><i class="fa fa-file-invoice-dollar"></i></a>
                        <a href="' . route('program.index', $row->slug) . '" class="edit btn btn-info btn-xs mb-1" title="Link" target="_blank"><i class="fa fa-external-link-alt"></i></a>';
                })
                // Server-side global search (biar tetap scalable)
                ->filter(function ($query) use ($request) {
                    $search = data_get($request->input('search'), 'value');
                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('program.title', 'like', "%{$search}%")
                                ->orWhere('program.slug', 'like', "%{$search}%")
                                ->orWhere('program.short_desc', 'like', "%{$search}%")
                                ->orWhere('organization.name', 'like', "%{$search}%");
                        });
                    }
                }, true)
                ->rawColumns(['action', 'nominal', 'status', 'stats', 'donate'])
                ->make(true);
        });
    }


    /**
     * Datatables Program Dashboard
     */
    public function datatablesProgramDashboard(Request $request)
    {
        // if ($request->ajax()) {
        $data = Program::select('program.*', 'organization.name as organization')->join('organization', 'organization.id', 'program.organization_id')->orderBy('program.donate_sum', 'DESC');

        $data = $data->where(function ($q) use ($data) {
            $q->where('is_recommended', 1)->orWhere('is_show_home', 1)->orWhere('is_urgent', 1);
        });

        if (isset($request->is_publish)) {
            $data = $data->where('is_publish', $request->is_publish)->where('end_date', '>', date('Y-m-d H:i:s'));
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q
                    ->where('program.title', 'like', '%' . $search . '%')
                    ->orWhere('program.slug', 'like', '%' . $search . '%')
                    ->orWhere('program.short_desc', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data->skip($start)->take($pageSize);

        $data = $data->get();

        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('nominal', function ($row) {
                $sum = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->sum('nominal_final');
                if ($sum > 0) {
                    $sum_percent = round(($sum / $row->nominal_approved) * 100, 2);
                } else {
                    $sum_percent = 0;
                }

                $spend = \App\Models\ProgramSpend::where('program_id', $row->id)->where('status', 'done')->sum('nominal_approved');
                if ($spend > 0 && $sum > 0) {
                    $spend_percent = round(($spend / $sum) * 100, 2);
                } else {
                    $spend_percent = 0;
                }

                $param = $row->id . ", '" . ucwords(str_replace("'", '', $row->title)) . "'";

                return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary('
                    . $param
                    . ')">
                        <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.'
                    . str_replace(',', '.', number_format($row->nominal_approved))
                    . '</span>
                        <br>
                        <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate('
                    . $param
                    . ')">
                        <i class="fa fa-money-bill icon-gradient bg-happy-green"></i> Rp.'
                    . number_format($sum)
                    . ' ('
                    . $sum_percent
                    . '%)</span>
                        <br>
                        <span class="badge badge-light" style="cursor:pointer" onclick="inpSpend('
                    . $param
                    . ')">
                        <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.'
                    . number_format($spend)
                    . ' ('
                    . $spend_percent
                    . '%)</span>';
            })
            ->addColumn('ads', function ($row) {
                $trans_prime1 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 00:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 09:59:59');
                if ($trans_prime1->count() > 9) {
                    $prime1_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 4) {
                    $prime1_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 2) {
                    $prime1_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 0) {
                    $prime1_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } else {
                    $prime1_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                }

                $trans_prime2 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 10:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 14:59:59');
                if ($trans_prime2->count() > 9) {
                    $prime2_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 4) {
                    $prime2_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 2) {
                    $prime2_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 0) {
                    $prime2_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } else {
                    $prime2_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                }

                $trans_prime3 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 15:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 23:59:59');
                if ($trans_prime3->count() > 9) {
                    $prime3_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 4) {
                    $prime3_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 2) {
                    $prime3_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 0) {
                    $prime3_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } else {
                    $prime3_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                }

                $prime1 = 'Jam 00:00 - 10:00 = ' . $prime1_ads;
                $prime2 = 'Jam 10:00 - 15:00 = ' . $prime2_ads;
                $prime3 = 'Jam 15:00 - 24:00 = ' . $prime3_ads;
                return $prime1 . '<br>' . $prime2 . '<br>' . $prime3;
            })
            ->addColumn('stats', function ($row) {
                $count_view = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_amount_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_payment_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'payment_type')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();

                $view = "<i class='fa fa-eye icon-gradient bg-malibu-beach'></i> " . number_format($count_view);
                $amount_page = "<i class='fa fa-money-bill icon-gradient bg-malibu-beach'></i> " . number_format($count_amount_page);
                $payment_page = "<i class='fa fa-credit-card icon-gradient bg-malibu-beach'></i> " . number_format($count_payment_page);

                if ($count_view > 0 && $count_amount_page > 0) {
                    $amount_per = round(($count_amount_page / $count_view) * 100, 2);
                } else {
                    $amount_per = 0;
                }

                if ($count_view > 0 && $count_payment_page > 0) {
                    $count_payment_page_per = round(($count_payment_page / $count_view) * 100, 2);
                } else {
                    $count_payment_page_per = 0;
                }

                return $view . '<br>' . $amount_page . ' (' . $amount_per . '%) <br>' . $payment_page . ' (' . $count_payment_page_per . '%)';
            })
            ->addColumn('donate', function ($row) {
                $count_view = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_form_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'form')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();

                $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> " . number_format($count_form_page);
                $checkout = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count('id');
                $count = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count('id');

                if ($count_view > 0 && $count_form_page > 0) {
                    $interest_per = round(($count_form_page / $count_view) * 100, 2);
                } else {
                    $interest_per = 0;
                }

                if ($checkout > 0 && $count_view > 0) {
                    $checkout_per = round(($checkout / $count_view) * 100, 2);
                } else {
                    $checkout_per = 0;
                }

                if ($count > 0 && $checkout > 0) {
                    $count_per = round(($count / $checkout) * 100, 2);
                } else {
                    $count_per = 0;
                }

                return $interest
                    . ' ('
                    . $interest_per
                    . '%)
                        <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '
                    . number_format($checkout)
                    . ' ('
                    . $checkout_per
                    . '%)
                        <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '
                    . number_format($count)
                    . ' ('
                    . $count_per
                    . '%)';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-xs">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'nominal', 'ads', 'stats', 'donate'])
            ->make(true);
        // }
    }

    /**
     * Get stats of visitor
     */
    public function statsVisitor(Request $request)
    {
        if ($request->type == 'landing_page') {
            return $this->analyticVisitorShow();
        } elseif ($request->type == 'amount') {
            return $this->analyticVisitorClickDonateShow();
        } elseif ($request->type == 'donate') {
            return $this->analyticVisitorDonateShow();
        } elseif ($request->type == 'paid') {
            return $this->analyticVisitorPaidShow();
        }
    }

    /**
     * Show analytic Visitor
     */
    public function analyticVisitorShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('program_id', $id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor click donate button
     */
    public function analyticVisitorClickDonateShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('program_id', $id)
                    ->where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor donate/transaction
     */
    public function analyticVisitorDonateShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('program_id', $id)
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor donate/transaction
     */
    public function analyticVisitorPaidShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('program_id', $id)
                    ->where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show Donate from datatable program
     */
    public function showDonate(Request $request)
    {
        // $program = Program::where('id', $id)->first();
    }

    /**
     * Show detail statistic Program
     */
    public function detailStats(Request $request)
    {
        $id = $request->id;
        $dn = date('Y-m-d');
        $program_name = Program::where('id', $id)->select('title')->first()->title;
        $avg_all = Transaction::where('program_id', $id)->avg('nominal_final');

        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'landing_page')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'amount')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'payment_type')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'form')
            ->count();
        $visitor_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->count();
        $visitor_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', 'success')
            ->count();

        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', 'success')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', '<>', 'success')
            ->count();
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', '<>', 'success')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->avg('nominal_final');
        $donate_today[] = $avg_all;

        $summary[] = Transaction::where('program_id', $id)->where('status', 'success')->sum('nominal_final');
        $summary[] = Transaction::where('program_id', $id)->where('status', 'success')->count();
        $summary[] = \App\Models\Payout::where('program_id', $id)->where('status', 'paid')->sum('nominal_approved');
        $summary[] = \App\Models\Payout::where('program_id', $id)->where('status', 'paid')->count();
        $summary[] = \App\Models\ProgramSpend::where('program_id', $id)->where('status', 'done')->where('type', 'ads')->sum('nominal_approved');
        $summary[] = \App\Models\ProgramSpend::where('program_id', $id)->where('status', 'done')->where('type', 'ads')->count();

        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'landing_page')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'amount')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'payment_type')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'form')->count();
        $visitor_all[] = Transaction::where('program_id', $id)->count();
        $visitor_all[] = Transaction::where('program_id', $id)->where('status', 'success')->count();

        $donate_all[] = Transaction::where('program_id', $id)->sum('nominal_final');
        $donate_all[] = Transaction::where('program_id', $id)->where('status', 'success')->sum('nominal_final');
        $donate_all[] = Transaction::where('program_id', $id)->where('status', '<>', 'success')->count();
        $donate_all[] = Transaction::where('program_id', $id)->where('status', '<>', 'success')->sum('nominal_final');
        $donate_all[] = $avg_all;
        $donate_all[] = $donate_today[5];

        // Visitor landing page
        $visitor_analytic = $this->analyticVisitorShow($id);
        $a = array_filter($visitor_analytic);
        if (count($a)) {
            $visitor_analytic_avg = array_sum($a) / count($a);
        } else {
            $visitor_analytic_avg = 0;
        }

        // Visitor count klik tombol donasi
        $click_donate = $this->analyticVisitorClickDonateShow($id);

        // Visitor count donate / transaction
        $donate_count = $this->analyticVisitorDonateShow($id);

        // Visitor count donate paid / success
        $donate_paid = $this->analyticVisitorPaidShow($id);

        // Per Day
        $list_date = Transaction::where('program_id', $id)->select(DB::Raw('DATE(created_at) day'))->groupBy('day')->orderBy('day', 'DESC')->get();
        // foreach ($list_date as $key => $value) {
        //     echo $value->day;
        //     echo "<br><br>";
        // }

        // Per tangal
        for ($i = 0; $i < 31; $i++) {
            $dt = sprintf('%02d', $i + 1);

            $jml_date = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->groupBy(DB::raw('Date(created_at)'))
                ->count();
            $count_per_date = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->count();
            $per_date_count[] = $count_per_date > 1 ? $count_per_date / $jml_date : 0;
            $per_date_nominal[] = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->avg('nominal_final');
        }

        // Per Jam (MASIH SALAH)
        $first_trans_date = Transaction::where('program_id', $id)->orderBy('created_at', 'asc')->select('created_at')->first()->created_at;
        $from = \Carbon\Carbon::parse($first_trans_date);
        $to = \Carbon\Carbon::parse($dn);
        $diff_in_days = $to->diffInDays($from);

        for ($i = 0; $i < 24; $i++) {
            $dt = sprintf('%02d', $i);

            $count_per_time = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-__ ' . $dt . '%')
                ->count();
            $per_time_count[] = $count_per_time > 1 ? round($count_per_time / $diff_in_days, 2) : 0;
            $per_time_nominal[] = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-__ ' . $dt . '%')
                ->avg('nominal_final');
        }

        return view('admin.program.stat', compact('program_name', 'visitor_today', 'donate_today', 'summary', 'visitor_all', 'donate_all', 'id', 'visitor_analytic', 'visitor_analytic_avg', 'click_donate', 'donate_count', 'donate_paid', 'per_date_count', 'per_date_nominal', 'per_time_count', 'per_time_nominal'));
    }

    /**
     * Show detail statistic Program
     */
    public function detailDonatur(Request $request)
    {
        echo 'list donatur';
        // return view('admin.program.donatur');
    }

    /**
     * Show detail statistic Program
     */
    public function detailFundraiser(Request $request)
    {
        echo 'list fundraiser';
        // return view('admin.program.fundraiser');
    }

    /**
     * Show Summary in this Program from datatable program
     */
    public function showSummary(Request $request)
    {
        $id = $request->id;
        $dn = date('Y-m-d');

        $payout_paid = \App\Models\Payout::where('status', 'paid')->where('program_id', $id)->sum('nominal_approved');
        $payout_req = \App\Models\Payout::where('status', 'request')->where('program_id', $id)->sum('nominal_approved');
        $payout_process = \App\Models\Payout::where('status', 'process')->where('program_id', $id)->sum('nominal_approved');
        $payout_reject = \App\Models\Payout::where('status', 'reject')->where('program_id', $id)->sum('nominal_approved');
        $payout_cancel = \App\Models\Payout::where('status', 'cancel')->where('program_id', $id)->sum('nominal_approved');
        $donate_sum = Transaction::select('id')->where('status', 'success')->where('program_id', $id)->sum('nominal_final');
        $ads_spent = \App\Models\ProgramSpend::where('program_id', $id)->where('type', 'ads')->where('status', 'done')->whereNotNull('date_approved')->sum('nominal_approved');
        $platform_fee = ($donate_sum * 5) / 100;
        $ads_fee = ($donate_sum * 20) / 100;
        $opex_fee = ($donate_sum * 2) / 100;

        $optimation_fee = \App\Models\Program::where('id', $id)->select('optimation_fee')->first()->optimation_fee;
        if ($optimation_fee > 0) {
            $optimation_fee_final = $donate_sum * ($optimation_fee / 100);
        } else {
            $optimation_fee_final = 0;
        }

        // hitung mana yg lebih besar ads_spent atau 20% anggaran ads, maka itu yg dimasukkan hitungan pengurangan penghimpunan
        if ($ads_spent > $ads_fee) {
            $final_ads_fee = $ads_spent;
        } else {
            $final_ads_fee = $ads_fee;
        }

        $final = $donate_sum - $platform_fee - $final_ads_fee - $opex_fee - $optimation_fee_final - $payout_paid;

        $data1 =
            '<div class="row">
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <td class="text-start">Total Donasi</td>
                            <td>Rp. '
            . number_format($donate_sum)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Platform Fee 5%</td>
                            <td>Rp. '
            . number_format($platform_fee)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">ADS Fee 20%</td>
                            <td>Rp. '
            . number_format($ads_fee)
            . ' | Rp. '
            . number_format($ads_spent)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Admin Bank 2%</td>
                            <td>Rp. '
            . number_format($opex_fee)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Optimasi Fee '
            . $optimation_fee
            . '%</td>
                            <td>Rp. '
            . number_format($optimation_fee_final)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '
            . number_format($payout_paid)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Sisa Penghimpunan</td>
                            <td>Rp. '
            . number_format($final)
            . '</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <th>Penyaluran</th>
                            <th>Nominal</th>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Diajukan</td>
                            <td>Rp. '
            . number_format($payout_req)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Sedang Diproses</td>
                            <td>Rp. '
            . number_format($payout_process)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '
            . number_format($payout_paid)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Ditolak</td>
                            <td>Rp. '
            . number_format($payout_reject)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Dibatalkan</td>
                            <td>Rp. '
            . number_format($payout_cancel)
            . '</td>
                        </tr>
                    </table>
                </div>
            </div>';
        return $data1;
    }

    /**
     * Show Donate from datatable program
     */
    public function showSpend(Request $request)
    {
        $data = \App\Models\ProgramSpend::where('program_id', $request->id)->orderBy('created_at', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('desc_request', 'like', '%' . $search . '%')
                    ->orWhere('nominal_approved', 'like', '%' . str_replace([',', '.'], '', $search) . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('date_approved', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data->skip($start)->take($pageSize);

        $data = $data->get();
        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return date('Y-m-d H:i', strtotime($row->date_approved));
            })
            ->addColumn('title', function ($row) {
                return ucwords($row->title);
            })
            ->addColumn('nominal', function ($row) {
                return number_format($row->nominal_approved);
            })
            ->addColumn('desc', function ($row) {
                return $row->desc_request;
            })
            ->addColumn('status', function ($row) {
                if ($row->type == 'ads') {
                    $type = '<span class="badge badge-info badge-sm">ADS</span>';
                } elseif ($row->type == 'operational') {
                    $type = '<span class="badge badge-warning badge-sm">OPERASIONAL</span>';
                } elseif ($row->type == 'payment_fee') {
                    $type = '<span class="badge badge-primary badge-sm">PAYMENT FEE</span>';
                } else {
                    // others
                    $type = '<span class="badge badge-success badge-sm">OTHERS</span>';
                }

                if ($row->status == 'draft') {
                    $status = '<span class="badge badge-info badge-sm">PENGAJUAN</span>';
                } elseif ($row->status == 'process') {
                    $status = '<span class="badge badge-warning badge-sm">DIPROSES</span>';
                } elseif ($row->status == 'done') {
                    $status = '<span class="badge badge-success badge-sm">SELESAI</span>';
                } elseif ($row->status == 'cancel') {
                    $status = '<span class="badge badge-secondary badge-sm">DIBATALKAN</span>';
                } else {
                    // reject
                    $status = '<span class="badge badge-danger badge-sm">DITOLAK</span>';
                }

                return $type . ' ' . $status;
            })
            ->rawColumns(['date', 'title', 'nominal', 'desc', 'status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function submitSpend(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'id_program' => 'required|numeric',
            'date_time' => 'required',
            'nominal' => 'required',
        ]);

        $data = new \App\Models\ProgramSpend();
        $data->program_id = $request->id_program;
        $data->title = trim($request->title);
        $data->nominal_request = str_replace('.', '', $request->nominal);
        $data->nominal_approved = str_replace('.', '', $request->nominal);
        $data->date_request = $request->date_time . ':00';
        $data->date_approved = $request->date_time . ':00';
        $data->approved_by = 1;
        $data->type = 'ads';
        $data->is_operational = 1;
        $data->status = 'done';
        $data->desc_request = trim($request->title);
        $data->save();

        echo 'success';
        // return redirect()->back();
    }

    public function donatePerformance(Request $request)
    {
        $data = [];
        $dn = date('d-m-Y');
        $jml_day_of = 20;
        $jml_program = 40;

        $program_trans = Transaction::where('status', 'success')
            ->where('created_at', '>=', date('Y-m-d', strtotime($dn . '-5 days')) . ' 00:00:00')
            ->groupBy('program_id')
            ->pluck('program_id');

        $program = Program::select('id', 'title', 'donate_sum')
            ->where('is_publish', 1)
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            // ->where(function ($q){
            //     $q->where('is_recommended', 1)->orWhere('is_show_home', 1)->orWhere('is_urgent', 1);
            // })
            ->whereIn('id', $program_trans)
            ->orderBy('donate_sum', 'DESC')
            ->limit($jml_program)
            ->get();
        foreach ($program as $v) {
            $donate = Transaction::select(DB::raw('sum(nominal_final) as sum'), DB::raw('count(id) as count'), DB::raw('DATE(created_at) as created_at'))
                ->where('program_id', $v->id)
                ->where('status', 'success')
                ->where('created_at', '>=', date('Y-m-d', strtotime($dn . '-' . $jml_day_of . ' days')) . ' 00:00:00')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'), 'DESC')
                ->limit($jml_program)
                ->get()
                ->toArray();
            $date = isset($donate[0]['created_at']) ? date('d-m-y', strtotime($donate[0]['created_at'])) : '-';
            $data[] = [
                'date' => $date,
                'title' => $v->title,
                'donate_sum' => $v->donate_sum,
                'donate' => $donate,
            ];
        }
        // dd($data);
        return view('admin.program.performance', compact('data'));
    }

    /**
     * Select2 Program
     */
    public function select2(Request $request)
    {
        $data = Program::query()->select('id', 'title', 'nominal_approved', 'slug');
        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('page')) {
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $data->get(),
            'extra_data' => [
                'last_page' => $last_page,
            ],
        ]);
    }
}
