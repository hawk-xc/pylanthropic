<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\WaBlastController;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Models\GrabOrganization;

class LeadsController extends Controller
{
    protected $GrabOrganizationColumn = [
        'id',
        'user_id',
        'platform_id',
        'name',
        'domicile',
        'address',
        'gtm',
        'twitter',
        'instagram',
        'facebook',
        'youtube',
        'description',
        'email',
        'phone',
        'is_partner',
        'platform',
        'is_interest',
        'is_affiliated',
        'created_at',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.leads.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function grabList(Request $request)
    {
        return view('admin.leads.grab_program.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function listOrganization(Request $request)
    {
        return view('admin.leads.org_list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function orgCreate(Request $request)
    {
        return view('admin.leads.org_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orgStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
            ]);

            $check_org = DB::table('grab_organization')->select('id')->where('name', $request->name)->first();

            if (!isset($check_org->id)) {
                DB::table('grab_organization')->insert([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'email' => $request->email,
                    'instagram' => $request->ig,
                    'facebook' => $request->fb,
                    'youtube' => $request->yt,
                    'twitter' => $request->tw,
                    'fb_pixel' => $request->pixel,
                    'gtm' => $request->gtm,
                    'description' => $request->desc,
                    'user_id' => date('ymdhis'),
                ]);
            } else {
                return redirect()->back()->with('error', 'Gagal tambah, duplikat nama lembaga');
            }

            return redirect()->back()->with('success', 'Berhasil tambah data Lembaga');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal tambah, ada kesalahan teknis');
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
        //
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
     * Edit Status Grab
     */
    public function editStatusGrab(Request $request)
    {
        $id_program = $request->id;
        $status = $request->status;
        $type = $request->type;
        $sc = 'style="cursor:pointer"';

        $leads_program = DB::table('grab_program')->select('name')->where('id', $id_program)->first();

        if (isset($leads_program->name)) {
            // update data grab_program
            if ($type == 'interest') {
                DB::table('grab_program')
                    ->select('id')
                    ->where('id', $id_program)
                    ->update(['is_interest' => $status]);
                if ($status == 1) {
                    $btn = '<span class="badge badge-sm badge-success" ' . $sc . ' onclick="setInterest(' . $id_program . ', 0)">Menarik</span>';
                } else {
                    $btn = '<span class="badge badge-sm badge-info" ' . $sc . ' onclick="setInterest(' . $id_program . ', 1)">Menarik?</span>';
                }
            } else {
                DB::table('grab_program')
                    ->select('id')
                    ->where('id', $id_program)
                    ->update(['is_taken' => $status]);
                if ($status == 1) {
                    $btn = '<span class="badge badge-sm badge-success" ' . $sc . ' onclick="setTaken(' . $id_program . ', 0)">Tergarap</span>';
                } else {
                    $btn = '<span class="badge badge-sm badge-info" ' . $sc . ' onclick="setTaken(' . $id_program . ', 1)">Garap?</span>';
                }
            }

            return [
                'status' => 'success',
                'name' => $leads_program->name,
                'btn' => $btn,
            ];
        } else {
            return ['status' => 'fail'];
        }
    }

    /**
     * Datatables Chat
     */
    public function grabDatatables(Request $request)
    {
        $data = \App\Models\GrabProgram::with(['grab_organization', 'leads_platform'])
            ->whereNotNull('user_id')
            ->orderBy('created_at', 'DESC');

        if (isset($request->interest)) {
            if ($request->interest == 1) {
                $data = $data->where('grab_program.is_interest', 1);
            }
        }

        if (isset($request->taken)) {
            if ($request->taken == 1) {
                $data = $data->where('grab_program.is_taken', 1);
            }
        }

        if (isset($request->jt20_ar)) {
            if ($request->jt20_ar == 1) {
                $data = $data->where('grab_program.collect_amount', '<=', 20000000);
            }
        }

        if (isset($request->jt50_ar)) {
            if ($request->jt50_ar == 1) {
                $data = $data->where('grab_program.collect_amount', '>=', 50000000);
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('grab_program.name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('headline', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('target_status', 'like', '%' . $search . '%')
                    ->orWhere('target_type', 'like', '%' . $search . '%')
                    ->orWhere('target_at', 'like', '%' . $search . '%')
                    //->orWhere('grab_organization.name', 'like', '%'.$search.'%')
                    ->orWhere('target_amount', 'like', '%' . $search . '%');
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
            ->addColumn('name', function ($row) {
                return '<a href="' . $row->permalink . '" target="_blank">' . $row->name . '</a>';
            })
            ->addColumn('images', function ($row) {
                return '<img src="' . $row->image_url . '" style="width:280px; height:auto;">';
            })
            ->addColumn('nominal', function ($row) {
                $target = $row->target_type == 'unlimited' && $row->target_amount == 0 ? 'Unlimited' : number_format($row->target_amount);
                $collect = number_format($row->collect_amount);
                $sc = 'style="cursor:pointer"';
                $btn_edit = '<a href="' . route('adm.leads.org.edit', $row->user_id) . '" target="_blank" class="badge badge-sm badge-warning"><i class="fa fa-edit"></i></a>';

                $lembaga = GrabOrganization::where('user_id', $row->user_id)->orWhere('id', $row->user_id)->first();

                // use ORM method instead of query builder;
                // $lembaga = DB::table('grab_organization')->where('user_id', $row->user_id)->orWhere('id', $row->user_id)->first();
                if (isset($lembaga->name)) {
                    $org = '<a href="#" onclick="detailOrg(' . $row->user_id . ')">' . $lembaga->name . '</a>';
                    $wa_param = "'" . $row->user_id . "','" . str_replace("'", '', $lembaga->name) . "'";
                    $wa = 'onclick="firstChat(' . $wa_param . ')"';

                    $i_mail = '<i class="fa fa-envelope"></i>';
                    $i_telp = '<i class="fa fa-phone"></i>';
                    $i_fb = 'FB'; //'<i class="fa fa-facebook-f"></i>';
                    $i_tw = 'TW'; //'<i class="fa fa-twitter"></i>';
                    $i_ig = 'IG'; //'<i class="fa fa-instagram"></i>';
                    $i_yt = 'YT'; //'<i class="fa fa-youtube"></i>';

                    $mail = is_null($lembaga->email) || $lembaga->email == '' ? '<span class="badge badge-sm badge-secondary">' . $i_mail . '</span>' : '<span class="badge badge-sm badge-success">' . $lembaga->email . '</span>';
                    $fb = is_null($lembaga->facebook) || $lembaga->facebook == '' ? '<span class="badge badge-sm badge-secondary">' . $i_fb . '</span>' : '<a href="' . $lembaga->facebook . '" target="_blank" class="badge badge-sm badge-success">' . $i_fb . '</a>';
                    $tw = is_null($lembaga->twitter) || $lembaga->twitter == '' ? '<span class="badge badge-sm badge-secondary">' . $i_tw . '</span>' : '<a href="' . $lembaga->twitter . '" target="_blank" class="badge badge-sm badge-success">' . $i_tw . '</a>';
                    $ig = is_null($lembaga->instagram) || $lembaga->instagram == '' ? '<span class="badge badge-sm badge-secondary">' . $i_ig . '</span>' : '<a href="' . $lembaga->instagram . '" target="_blank" class="badge badge-sm badge-success">' . $i_ig . '</a>';
                    $yt = is_null($lembaga->youtube) || $lembaga->youtube == '' ? '<span class="badge badge-sm badge-secondary">' . $i_yt . '</span>' : '<a href="' . $lembaga->youtube . '" target="_blank" class="badge badge-sm badge-success">' . $i_yt . '</a>';
                    $telp = is_null($lembaga->phone) || $lembaga->phone == '' ? '<span class="badge badge-sm badge-secondary">' . $i_telp . '</span>' : '<span class="badge badge-sm badge-success" ' . $wa . ' ' . $sc . '>' . $lembaga->phone . '</span>';
                    $last_wa = '<span class="badge badge-sm badge-warning">-</span>';
                    $platform = '<span class="badge badge-sm badge-light">' . $lembaga->platform . '</span>';

                    return $org . '<br>' . $telp . ' ' . $last_wa . '<br>' . $mail . ' ' . $fb . ' ' . $tw . ' ' . $ig . ' ' . $yt . ' ' . $btn_edit . '<br>' . $target . '<br>' . $collect . '<br>' . $row->target_status . '<br>' . $platform;
                } else {
                    return 'Lembaga Tidak Ditemukan ' . $btn_edit . '<br>' . $target . '<br>' . $collect . '<br>' . $row->target_status;
                }
            })
            ->addColumn('platform', function ($row) {
                return $row->leads_platform ? $row->leads_platform->name : '-';
            })
            ->addColumn('date', function ($row) {
                $sc = 'style="cursor:pointer"';
                $start_date = '<i class="fa fa-play-circle"></i> ' . $row->program_created_at;
                $end_date = is_null($row->target_at) && is_null($row->target_status) ? 'Unlimited' : date('Y-m-d', strtotime($row->target_at));
                $end_date = '<i class="fa fa-stop-circle"></i> ' . $end_date;
                $created = '<i class="fa fa-pencil-alt"></i> ' . date('Y-m-d', strtotime($row->created_at));

                $interest = $row->is_interest == 1 ? '<span id="btninterest_' . $row->id . '"><span class="badge badge-sm badge-success" ' . $sc . ' onclick="setInterest(' . $row->id . ', 0)">Menarik</span></span>' : '<span id="btninterest_' . $row->id . '"><span class="badge badge-sm badge-info" ' . $sc . ' onclick="setInterest(' . $row->id . ',1)">Menarik?</span></span>';
                $taken = $row->is_taken == 1 ? '<span id="btntaken_' . $row->id . '"><span class="badge badge-sm badge-success" ' . $sc . ' onclick="setTaken(' . $row->id . ', 0)">Tergarap</span></span>' : '<span id="btntaken_' . $row->id . '"><span class="badge badge-sm badge-info" ' . $sc . ' onclick="setTaken(' . $row->id . ', 1)">Garap?</span></span>';
                return $start_date . '<br>' . $end_date . '<br>' . $interest . '<br>' . $taken . '<br>' . $created;
            })
            ->addColumn('headline', function ($row) {
                return $row->headline ?? '-';
            })
            ->rawColumns(['name', 'images', 'nominal', 'date', 'headline'])
            ->make(true);
    }

    /**
     * Datatables Chat
     */
    public function orgDatatables(Request $request)
    {
        $data = Cache::remember('grab_organization_data_with_programs_count', 60, function () {
            return GrabOrganization::withCount([
                'grab_programs as garap_count' => function ($query) {
                    $query->where('is_taken', 1);
                },
                'grab_programs as menarik_count' => function ($query) {
                    $query->where('is_interest', 1);
                }
            ])->orderBy('created_at', 'DESC')->get();
        });

        if (isset($request->ada_wa)) {
            if ($request->ada_wa == 1) {
                $data = $data->whereNotNull('phone');
            }
        }

        if (isset($request->ada_email)) {
            if ($request->ada_email == 1) {
                $data = $data->whereNotNull('email');
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        // Custom column search for 'informasi_program'
        $columnSearch = $request->input('columns');
        if (isset($columnSearch[3]['search']['value']) && is_numeric($columnSearch[3]['search']['value'])) {
            $numSearch = (int) $columnSearch[3]['search']['value'];
            $data = $data->filter(function ($item) use ($numSearch) {
                return ($item->garap_count === $numSearch) || ($item->menarik_count === $numSearch);
            });
        }

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('twitter', 'like', '%' . $search . '%')
                    ->orWhere('instagram', 'like', '%' . $search . '%')
                    ->orWhere('facebook', 'like', '%' . $search . '%')
                    ->orWhere('youtube', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        // Manual Sorting
        $order = $request->input('order');
        if (!empty($order)) {
            $order_column_index = $order[0]['column'];
            $order_dir = $order[0]['dir'];
            $columns = $request->input('columns');
            $column_def = $columns[$order_column_index];

            if ($column_def['orderable'] === 'true') {
                if ($order_column_index == 3) { // "Informasi Program" column
                    $sortMethod = $order_dir === 'asc' ? 'sortBy' : 'sortByDesc';
                    $data = $data->{$sortMethod}(function ($row) {
                        return ($row->garap_count ?? 0) + ($row->menarik_count ?? 0);
                    });
                } else { // Default sorting for other columns
                    $column_name = $column_def['data'];
                    $sortMethod = $order_dir === 'asc' ? 'sortBy' : 'sortByDesc';
                    $data = $data->{$sortMethod}($column_name);
                }
            }
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data = $data->skip($start)->take($pageSize);

        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->order(function () {})
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->is_affiliated ? '<a href="#" target="_blank"><i class="fas fa-handshake"></i> ' . $row->name . '</a>' : '<a href="#" target="_blank">' . $row->name . '</a>';
            })
            ->addColumn('contact', function ($row) {
                $cursorStyle = 'style="cursor:pointer"';
                $faPhone = '<i class="fa fa-phone"></i>';
                $faMail = '<i class="fa fa-envelope"></i>';

                $waParams = "'" . $row->user_id . "','" . str_replace("'", '', $row->name) . "'";

                $waAttr = 'onclick="firstChat(' . $waParams . ')"';

                $telp = is_null($row->phone) || $row->phone == ''
                    ? '<a style="cursor: pointer;" onClick="openUpdateOrganizationPhoneModal(`' . $row->id . '`, `' . $row->name . '`)" class="badge badge-sm badge-primary">' . $faPhone . '</a>'
                    : '<a style="cursor: pointer;" onClick="openUpdateOrganizationPhoneModal(`' . $row->id . '`, `' . $row->name . '`)" class="badge badge-sm badge-success" ' . $waAttr . ' ' . $cursorStyle . '>' . $faPhone . ' ' . $row->phone . '</a>';

                $mail = is_null($row->email) || $row->email == ''
                    ? '<span class="badge badge-sm badge-secondary">' . $faMail . '</span>'
                    : '<span class="badge badge-sm badge-success">' . $row->email . '</span>';

                $whatsapp =  '<a style="cursor: pointer;" onClick="openDonaturLoyalModal(`' . $row->name . '`)" class="badge badge-sm badge-success"><i class="fa fa-database"></i></button>';

                return $telp . ' ' . $mail;
            })
            ->addColumn('socmed', function ($row) {
                $sc = 'style="cursor:pointer"';
                $i_fb = 'FB';
                $i_tw = 'TW';
                $i_ig = 'IG';
                $i_yt = 'YT';

                $fb = is_null($row->facebook) || $row->facebook == '' ? '<span class="badge badge-sm badge-secondary">' . $i_fb . '</span>' : '<a href="' . $row->facebook . '" target="_blank" class="badge badge-sm badge-success">' . $i_fb . '</a>';
                $tw = is_null($row->twitter) || $row->twitter == '' ? '<span class="badge badge-sm badge-secondary">' . $i_tw . '</span>' : '<a href="' . $row->twitter . '" target="_blank" class="badge badge-sm badge-success">' . $i_tw . '</a>';
                $ig = is_null($row->instagram) || $row->instagram == '' ? '<span class="badge badge-sm badge-secondary">' . $i_ig . '</span>' : '<a href="' . $row->instagram . '" target="_blank" class="badge badge-sm badge-success">' . $i_ig . '</a>';
                $yt = is_null($row->youtube) || $row->youtube == '' ? '<span class="badge badge-sm badge-secondary">' . $i_yt . '</span>' : '<a href="' . $row->youtube . '" target="_blank" class="badge badge-sm badge-success">' . $i_yt . '</a>';

                return $fb . ' ' . $tw . ' ' . $ig . ' ' . $yt;
            })
            ->addColumn('action', function ($row) {
                if ($row->user_id) {
                    $btn_edit = '<a href="' . route('adm.leads.org.edit', $row->user_id) . '" target="_blank" class="badge badge-sm badge-warning"><i class="fa fa-edit"></i></a>';
                    return $btn_edit;
                }
                return '-';
            })
            ->addColumn('informasi_program', function ($row) {
                $garapCount = $row->garap_count ?? 0;
                $menarikCount = $row->menarik_count ?? 0;

                if ($garapCount == 0 && $menarikCount == 0) {
                    return 'belum terdapat informasi';
                }

                $garapBadge = '<span class="badge badge-sm badge-info">Garap: ' . $garapCount . '</span>';
                $menarikBadge = '<span class="badge badge-sm badge-success">Menarik: ' . $menarikCount . '</span>';
                return $garapBadge . ' ' . $menarikBadge;
            })
            ->rawColumns(['name', 'contact', 'socmed', 'action', 'informasi_program'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editOrganization($id)
    {
        $org = DB::table('grab_organization')->where('user_id', $id)->first();

        return view('admin.leads.edit_org', compact('org'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOrganization(Request $request, $id)
    {
        try {
            $data = DB::table('grab_organization')
                ->where('user_id', $id)
                ->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'email' => $request->email,
                    'instagram' => $request->ig,
                    'facebook' => $request->fb,
                    'youtube' => $request->yt,
                    'twitter' => $request->tw,
                    'fb_pixel' => $request->pixel,
                    'gtm' => $request->gtm,
                    'description' => $request->desc,
                    'updated_at' => $request->date('Y-m-d H:i:s'),
                ]);

            return redirect()->back()->with('success', 'Berhasil update data Lembaga');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update, ada kesalahan teknis');
        }
    }

    /**
     * WA Organization
     */
    public function waOrganization(Request $request)
    {
        $id_org = $request->id;
        $type = $request->type;

        $org = DB::table('grab_organization')->select('name', 'phone', 'id')->where('user_id', $id_org)->first();

        if (isset($org->phone)) {
            // type of chat
            if ($type == 'fc') {
                // FC here
                $chat =
                    "Assalamu'alaikum wr.wb, admin *" .
                    ucwords(trim($org->name)) .
                    "*.
Saya Alifa dari tim Bantubersama.com (platform galang dana).
Kami lihat program Anda " .
                    ucwords(trim($org->name)) .
                    " sangat bagus dan menarik sesuai dengan minat donatur kami.
Bersedia kami bantu promosikan dan optimasi donasinya?🙏🏻✨";
                (new WaBlastController())->sentWAGeneral($org->phone, $chat);
            } else {
                // selain FC
            }

            return [
                'status' => 'success',
                'org' => $org->name,
            ];
        } else {
            return ['status' => 'fail'];
        }
    }

    /**
     * Ambil data dari platform lain
     *
     */
    public function grabLeadsAmalsholeh(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
        } else {
            $id = 1;
        }

        $count_inp_org = 0;
        $count_inp_program = 0;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://core.sholeh.app/api/v1/programs?s=a&per_page=3000&page=' . $id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if (isset($res->data)) {
                $data = $res->data->data;

                for ($i = 0; $i < count($data); $i++) {
                    // ambil data lembaganya
                    if (isset($data[$i]->user->id)) {
                        $org = DB::table('grab_organization')
                            ->select('user_id')
                            ->where('user_id', $data[$i]->user->id)
                            ->first();
                        if (!isset($org->user_id)) {
                            if (isset($data[$i]->user->description)) {
                                $desc = $this->removeEmoji($data[$i]->user->description);
                            } else {
                                $desc = null;
                            }

                            $user_id = DB::table('grab_organization')->insertGetId([
                                'user_id' => $data[$i]->user->id,
                                'name' => $data[$i]->user->name,
                                'avatar' => $data[$i]->user->avatar,
                                'domicile' => isset($data[$i]->user->domicile->text) ? $data[$i]->user->domicile->text : null,
                                'address' => $data[$i]->user->address,
                                'fb_pixel' => isset($data[$i]->user->fb_pixel) ? $data[$i]->user->fb_pixel : null,
                                'gtm' => isset($data[$i]->user->gtm) ? $data[$i]->user->gtm : null,
                                'twitter' => isset($data[$i]->user->twitter) ? $data[$i]->user->twitter : null,
                                'instagram' => isset($data[$i]->user->instagram) ? $data[$i]->user->instagram : null,
                                'facebook' => isset($data[$i]->user->facebook) ? $data[$i]->user->facebook : null,
                                'youtube' => isset($data[$i]->user->youtube) ? $data[$i]->user->youtube : null,
                                'description' => $desc,
                                // 'created_at'  => date('Y-m-d H:i:s'),
                                // 'updated_at'  => date('Y-m-d H:i:s')
                            ]);

                            $count_inp_org++;
                        } else {
                            $user_id = $org->user_id;
                        }
                    } else {
                        $user_id = null;
                    }

                    // ambil data program
                    $program = DB::table('grab_program')->select('id_grab')->where('id_grab', $data[$i]->id)->first();
                    if (!isset($program->id_grab)) {
                        DB::table('grab_program')->insertGetId([
                            'id_grab' => $data[$i]->id,
                            'category_slug' => $data[$i]->category_slug,
                            'type' => $data[$i]->type,
                            'name' => isset($data[$i]->name) ? $this->removeEmoji($data[$i]->name) : null,
                            'slug' => $data[$i]->slug,
                            'permalink' => $data[$i]->permalink,
                            'headline' => isset($data[$i]->headline) ? $this->removeEmoji($data[$i]->headline) : null,
                            'content' => isset($data[$i]->content) ? $this->removeEmoji($data[$i]->content) : null,
                            'status' => $data[$i]->status,
                            'target_status' => $data[$i]->target_status,
                            'target_type' => $data[$i]->target_type,
                            'target_at' => $data[$i]->target_at,
                            'target_amount' => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->target_amount),
                            'collect_amount' => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->collect_amount),
                            'remaining_amount' => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->remaining_amount),
                            'over_at' => $data[$i]->over_at != 'null' ? date('Y-m-d H:i:s', strtotime($data[$i]->over_at)) : null,
                            'is_featured' => $data[$i]->is_featured,
                            'is_populer_search' => $data[$i]->is_populer_search,
                            'status_percent' => $data[$i]->status_percent,
                            'status_date' => $data[$i]->status_date == 'false' ? null : $data[$i]->status_date,
                            'image_url' => $data[$i]->image_url,
                            'image_url_thumb' => $data[$i]->image_url_thumb,
                            'user_id' => $user_id,
                            'total_donatur' => str_replace('.', '', $data[$i]->total_donatur),
                            'fb_pixel' => $data[$i]->fb_pixel,
                            'gtm' => $data[$i]->gtm,
                            'toggle_dana' => $data[$i]->toggle_dana,
                            'program_created_at' => date('Y-m-d', strtotime($data[$i]->program_created_at)) ? date('Y-m-d', strtotime($data[$i]->program_created_at)) : date('Y-m-d'),
                            'tags_name' => isset($data[$i]->tags->title) ? $data[$i]->tags->title : null,
                            'is_favorite' => $data[$i]->is_favorite == 'false' ? 0 : 1,
                            'fund_display' => $data[$i]->fund_display,
                        ]);
                        $count_inp_program++;
                    }
                }
            } else {
                echo '<br>no data';
            }

            echo 'FINISHED, <br>Total Ambil Data : ' . count($data);
            echo '<br>Total Program Bagu : ' . $count_inp_program;
            echo '<br>Total Lembaga Bagu : ' . $count_inp_org;
        }
    }

    /**
     * Ambil data dari platform lain
     */
    public function grabLeadsSharingHappiness(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
        } else {
            $id = 1;
        }

        $count_inp_org = 0;
        $count_inp_program = 0;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://be.sharinghappiness.org/api/v1/program?keyword=a&perPage=2000&page=' . $id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if (isset($res->result)) {
                $data = $res->result->data;

                for ($i = 0; $i < count($data); $i++) {
                    // ambil data lembaganya
                    if (isset($data[$i]->user->name)) {
                        $org = DB::table('grab_organization')
                            ->select('user_id')
                            ->where('name', $data[$i]->user->name)
                            ->first();
                        if (!isset($org->user_id)) {
                            $user_id = DB::table('grab_organization')->insertGetId([
                                'user_id' => date('ymdhis'),
                                'name' => $data[$i]->user->name,
                                'avatar' => $data[$i]->user->picture,
                                'platform' => 'sharinghappiness',
                            ]);

                            $count_inp_org++;
                        } else {
                            $user_id = $org->user_id;
                        }
                    } else {
                        $user_id = null;
                    }

                    // ambil data program
                    $program = DB::table('grab_program')->select('id_grab')->where('id_grab', $data[$i]->id)->first();
                    if (!isset($program->id_grab) && isset($data[$i]->galleries[0]->image) && !is_null($data[$i]->galleries[0]->image) && !is_null($data[$i]->cover_picture->image)) {
                        DB::table('grab_program')->insertGetId([
                            'id_grab' => $data[$i]->id,
                            'category_slug' => isset($data[$i]->category->slug) ? $data[$i]->category->slug : null,
                            'name' => isset($data[$i]->title) ? $this->removeEmoji($data[$i]->title) : null,
                            'slug' => $data[$i]->slug,
                            'permalink' => 'https://sharinghappiness.org/' . $data[$i]->slug,
                            'headline' => isset($data[$i]->city->name) ? $this->removeEmoji($data[$i]->city->name) : null,
                            'content' => null,
                            'status' => $data[$i]->status,
                            'target_status' => null,
                            'target_type' => null,
                            'target_at' => $data[$i]->end_date,
                            'target_amount' => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->target),
                            'collect_amount' => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->collected),
                            'remaining_amount' => 0,
                            'over_at' => $data[$i]->end_date != 'null' && date('Y', strtotime($data[$i]->end_date)) > 1970 ? $data[$i]->end_date : null,
                            'is_featured' => $data[$i]->is_optimized,
                            'is_populer_search' => $data[$i]->is_recommended,
                            'status_percent' => $data[$i]->discount_price,
                            'status_date' => null,
                            'image_url' => isset($data[$i]->galleries[0]->image) ? $data[$i]->galleries[0]->image : $data[$i]->cover_picture->image,
                            'image_url_thumb' => $data[$i]->cover_picture->image,
                            'user_id' => $user_id,
                            'total_donatur' => str_replace('.', '', $data[$i]->transaction_count),
                            'fb_pixel' => null,
                            'gtm' => null,
                            'toggle_dana' => null,
                            'program_created_at' => date('Y-m-d', strtotime($data[$i]->created_at)) ? date('Y-m-d', strtotime($data[$i]->created_at)) : date('Y-m-d'),
                            'tags_name' => null,
                            'is_favorite' => 0,
                            'fund_display' => null,
                        ]);
                        $count_inp_program++;
                    }
                }
            } else {
                echo '<br>no data';
            }

            echo 'FINISHED, ';
            echo '<br>Total Ambil Data : ' . count($data);
            echo '<br>Total Program Baru : ' . $count_inp_program;
            echo '<br>Total Lembaga Baru : ' . $count_inp_org;
        }
    }

    /**
     * remove emoji
     */
    function removeEmoji($string)
    {
        // Match Enclosed Alphanumeric Supplement
        $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
        $clear_string = preg_replace($regex_alphanumeric, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Supplemental Symbols and Pictographs
        $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
        $clear_string = preg_replace($regex_supplemental, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        $clear_string = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $clear_string);
        $clear_string = preg_replace('/\x{FE0F}/u', '', $clear_string);
        $clear_string = preg_replace('/[^\x20-\x7E]/', '', $clear_string);
        $clear_string = preg_replace('/[\x{1F600}-\x{1F64F}|\x{1F300}-\x{1F5FF}|\x{1F680}-\x{1F6FF}|\x{1F700}-\x{1F77F}|\x{1F780}-\x{1F7FF}|\x{1F800}-\x{1F8FF}|\x{1F900}-\x{1F9FF}|\x{1FA00}-\x{1FA6F}|\x{1FA70}-\x{1FAFF}]/u', '', $clear_string);

        return $clear_string;
    }

    protected function getLeadsDataFromApi($name, $data_count, $page, $search, $category_id = null)
    {
        $raw_name = str_replace('_', ' ', $name);
        $raw_name = ucwords($raw_name);
        $platform_id = \App\Models\LeadsPlatform::select(['id', 'name'])
            ->where('name', 'like', '%' . $raw_name . '%')
            ->first()->id;

        switch (strtolower($name)) {
            case 'raih_mimpi':
                $count_inp_org = 0;
                $count_inp_program = 0;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://api.raihmimpi.id/campaign?page='.$page);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'terjadi kesalahan saat mengembalikan data!',
                    ]);
                }

                $res = json_decode($response);

                foreach ($res as $index => $item) {
                    $organization_id = null;

                    $org = GrabOrganization::where('name', $item->NAMA_LENGKAP)->first();

                    if (!$org) {
                        $new_org = new GrabOrganization();
                        $new_org->user_id = null;
                        $new_org->platform_id = $platform_id;
                        $new_org->name = $item->NAMA_LENGKAP;
                        $new_org->avatar = null;
                        $new_org->description = null;

                        $new_org->is_affiliated = \App\Models\Organization::whereJsonContains('alias_names', $item->NAMA_LENGKAP)->where('name', $item->NAMA_LENGKAP)->exists() ? 1 : 0;

                        $new_org->save();

                        $count_inp_org++;
                        $organization_id = $new_org->id;
                    } else {
                        $organization_id = $org->id;
                    }

                    // store the program
                    if ($organization_id) {
                        $program = \App\Models\GrabProgram::where('grab_organization_id', $organization_id)->where('slug', $item->SLUG)->first();

                        if ($program === null) {
                            $prog_url = 'https://raihmimpi.id/_next/data/gf2TnbYAL_Va85990GwEu/campaign/'.$item->SLUG.'.json?SLUG='.$item->SLUG;

                            $program_curl = curl_init();
                            curl_setopt($program_curl, CURLOPT_URL, $prog_url);
                            curl_setopt($program_curl, CURLOPT_RETURNTRANSFER, 1);
                            $program_response = curl_exec($program_curl);
                            $program_err = curl_error($program_curl);
                            curl_close($program_curl);

                            $program_res = json_decode($program_response);

                            $prog_data = $program_res->pageProps->campaign;

                            try {
                                $new_program = new \App\Models\GrabProgram();
                                $new_program->user_id = $organization_id;
                                $new_program->platform_id = $platform_id;
                                $new_program->grab_organization_id = $organization_id;
                                $new_program->id_grab = $prog_data->ID ?? null;
                                $new_program->name = $prog_data->CAMPAIGN_NAME ?? null;
                                $new_program->slug = $prog_data->SLUG ?? null;
                                $new_program->permalink = 'https://raihmimpi.id/campaign/' . $prog_data->SLUG;
                                $new_program->target_status = null;
                                $new_program->target_amount = $prog_data->TARGET_DONASI_UANG;
                                $new_program->collect_amount = $prog_data->TOTAL_DONASI;
                                $new_program->over_at = \Carbon\Carbon::createFromFormat('Y-m-d', $prog_data->END_DIBUAT)->format('Y-m-d H:i:s');
                                $new_program->status_percent = null;
                                $new_program->image_url = $prog_data->images[0];
                                $new_program->program_created_at = null;

                                $simpan = $new_program->save();
                                $count_inp_program++;
                            } catch (Exception $e) {
                                return response()->json([
                                    'status' => 'failed',
                                    'message' => 'terjadi kesalahan saat mengembalikan data!',
                                ]);
                            }
                        }
                    }
                }

                // refresh cache
                Cache::forget('leads_organizations:all');
                Cache::forget('leads_programs:all');
                Cache::forget('leads_platforms:all');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);
            case 'amal_sholeh':
                $count_inp_org = 0;
                $count_inp_program = 0;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://core.sholeh.app/api/v1/programs?s=' . $search . '&per_page=' . $data_count . '&page=' . $page);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'terjadi kesalahan saat mengembalikan data!',
                    ]);
                }

                $res = json_decode($response);

                if (!isset($res->data)) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Data tidak valid dari API',
                    ]);
                }

                $data = $res->data->data;

                foreach ($data as $index => $item) {
                    $organization_id = null;

                    if (isset($item->user)) {
                        $org = GrabOrganization::where('name', $item->user->name)->first();

                        if (!$org) {
                            $desc = isset($item->user->description) ? $this->removeEmoji($item->user->description) : '-';
                            $new_org = new GrabOrganization();
                            $new_org->user_id = $item->user->id;
                            $new_org->platform_id = $platform_id;
                            $new_org->name = $item->user->name;
                            $new_org->avatar = $item->user->avatar;
                            $new_org->description = $desc;

                            $new_org->is_affiliated = \App\Models\Organization::whereJsonContains('alias_names', $item->user->name)->where('name', $item->NAMA_LENGKAP)->exists() ? 1 : 0;

                            $new_org->save();

                            $count_inp_org++;
                            $organization_id = $new_org->id;
                        } else {
                            $organization_id = $org->id;
                        }
                    }

                    // store the program
                    if ($organization_id) {
                        $program = \App\Models\GrabProgram::where('grab_organization_id', $organization_id)->where('slug', $item->slug)->first();

                        if ($program === null) {
                            try {
                                $new_program = new \App\Models\GrabProgram();
                                $new_program->user_id = $organization_id;
                                $new_program->platform_id = $platform_id;
                                $new_program->grab_organization_id = $organization_id;
                                $new_program->id_grab = $item->id ?? null;
                                $new_program->name = $item->name;
                                $new_program->slug = $item->slug;
                                $new_program->permalink = $item->permalink;
                                $new_program->target_status = $item->status;
                                $new_program->target_amount = (int) str_replace(['Rp', ' ', '.'], '', $item->target_amount);
                                $new_program->collect_amount = (int) str_replace(['Rp', ' ', '.'], '', $item->target_at);
                                $new_program->over_at = \Carbon\Carbon::parse($item->target_at)->format('Y-m-d H:i:s');
                                $new_program->status_percent = $item->status_percent;
                                $new_program->image_url = $item->image_url ?? '-';
                                $new_program->headline = $item->headline;
                                $new_program->program_created_at = \Carbon\Carbon::createFromFormat('d M Y', $item->program_created_at)->format('Y-m-d') . ' 00:00:00';

                                $new_program->save();
                                $count_inp_program++;
                            } catch (Exception $e) {
                                return response()->json([
                                    'status' => 'failed',
                                    'message' => 'terjadi kesalahan saat mengembalikan data!',
                                ]);
                            }
                        }
                    }
                }

                // refresh cache
                Cache::forget('leads_organizations:all');
                Cache::forget('leads_programs:all');
                Cache::forget('leads_platforms:all');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);
            case 'sharing_happiness':
                $count_inp_org = 0;
                $count_inp_program = 0;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://be.sharinghappiness.org/api/v1/program?keyword=' . $search . '&perPage=' . $data_count . '&page=' . $page);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'terjadi kesalahan saat mengembalikan data!',
                    ]);
                }

                $res = json_decode($response);

                if (!isset($res->result)) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Data tidak valid dari API',
                    ]);
                }

                $data = $res->result->data;

                foreach ($data as $index => $item) {
                    $organization_id = null;

                    if (isset($item->user)) {
                        $org = GrabOrganization::where('name', $item->user->name)->first();

                        if (!$org) {
                            $desc = isset($item->user->about) ? $this->removeEmoji($item->user->profile->about) : '-';
                            $new_org = new GrabOrganization();
                            $new_org->user_id = $item->user_id;
                            $new_org->platform_id = $platform_id;
                            $new_org->name = $item->user->name;
                            $new_org->avatar = $item->user->picture;
                            $new_org->description = $desc;

                            $new_org->is_affiliated = \App\Models\Organization::whereJsonContains('alias_names', $item->user->name)->where('name', $item->user->name)->exists() ? 1 : 0;

                            $new_org->save();

                            $count_inp_org++;
                            $organization_id = $new_org->id;
                        } else {
                            $organization_id = $org->id;
                        }
                    }

                    // store the program
                    if ($organization_id) {
                        $program = \App\Models\GrabProgram::where('grab_organization_id', $organization_id)->where('slug', $item->slug)->first();

                        if ($program === null) {
                            try {
                                $new_program = new \App\Models\GrabProgram();
                                $new_program->user_id = $organization_id;
                                $new_program->platform_id = $platform_id;
                                $new_program->grab_organization_id = $organization_id;
                                $new_program->id_grab = $item->id ?? null;
                                $new_program->name = $item->title;
                                $new_program->slug = $item->slug;
                                $new_program->permalink = 'https://sharinghappiness.org/' . $item->slug;
                                $new_program->target_status = $item->status;
                                $new_program->target_amount = $item->target;
                                $new_program->collect_amount = $item->collected;
                                $new_program->over_at = $item->end_date;
                                $new_program->status_percent = null;
                                $new_program->image_url = $item->cover_picture->image ?? '-';
                                $new_program->program_created_at = $item->created_at;

                                $simpan = $new_program->save();
                                $count_inp_program++;
                            } catch (Exception $e) {
                                return response()->json([
                                    'status' => 'failed',
                                    'message' => 'terjadi kesalahan saat mengembalikan data!',
                                ]);
                            }
                        }
                    }
                }

                // refresh cache
                Cache::forget('leads_organizations:all');
                Cache::forget('leads_programs:all');
                Cache::forget('leads_platforms:all');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);
            case 'bantu_tetangga':
                $count_inp_org = 0;
                $count_inp_program = 0;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://core.bantutetangga.com/campaign?page=' . $page . '&str=' . $search . '&per_page=' . $data_count);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'terjadi kesalahan saat mengembalikan data!'
                    ]);
                }

                $res = json_decode($response);

                if (!isset($res->data)) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Data tidak valid dari API',
                    ]);
                }

                $data = $res->data->data;

                foreach ($data as $index => $item) {
                    $organization_id = null;

                    if (isset($item->slug)) {
                        $org = GrabOrganization::where('name', $item->campaigner->name)->first();

                        if (!$org) {
                            $org_curl = curl_init();
                            curl_setopt($org_curl, CURLOPT_URL, 'https://core.bantutetangga.com/public/campaigner/' . $item->campaigner->slug);
                            curl_setopt($org_curl, CURLOPT_RETURNTRANSFER, 1);
                            $org_response = curl_exec($org_curl);
                            $org_err = curl_error($org_curl);
                            curl_close($org_curl);

                            if (!$org_err) {
                                $org_data = json_decode($org_response);

                                if (isset($org_data->data)) {
                                    $org_info = $org_data->data;
                                    $desc = isset($org_info->bio) ? $this->removeEmoji($org_info->bio) : '-';

                                    $new_org = new GrabOrganization();
                                    $new_org->user_id = $org_info->id;
                                    $new_org->platform_id = $platform_id;
                                    $new_org->name = $org_info->name;
                                    $new_org->avatar = $org_info->logo;
                                    $new_org->description = $desc;

                                    $new_org->is_affiliated = \App\Models\Organization::whereJsonContains('alias_names', $org_info->name)->where('name', $org_info->name)->exists() ? 1 : 0;

                                    $new_org->save();
                                    $count_inp_org++;
                                    $organization_id = $new_org->id;
                                }
                            }
                        } else {
                            $organization_id = $org->id;
                        }
                    }

                    if ($organization_id) {
                        $program = \App\Models\GrabProgram::where('grab_organization_id', $organization_id)->where('slug', $item->slug)->first();

                        if ($program === null) {
                            try {
                                $new_program = new \App\Models\GrabProgram();
                                $new_program->user_id = $organization_id;
                                $new_program->platform_id = $platform_id;
                                $new_program->grab_organization_id = $organization_id;
                                $new_program->id_grab = $index ?? null;
                                $new_program->name = $item->title;
                                $new_program->slug = $item->slug;
                                $new_program->permalink = 'https://bantutetangga.com/campaign/' . $item->slug;
                                $new_program->target_status = $item->status;
                                $new_program->target_amount = $item->target;
                                $new_program->collect_amount = $item->funds;
                                $new_program->over_at = $item->expired_at;
                                $new_program->status_percent = $item->percentage;
                                $new_program->image_url = $item->cover;
                                $new_program->program_created_at = \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                                // Set other fields as needed

                                $simpan = $new_program->save();
                                $count_inp_program++;
                            } catch (Exception $e) {
                                return response()->json([
                                    'status' => 'failed',
                                    'message' => 'terjadi kesalahan saat mengembalikan data!'
                                ]);
                            }
                        }
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);

                // refresh cache
                Cache::forget('leads_organizations:all');
                Cache::forget('leads_programs:all');
                Cache::forget('leads_platforms:all');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);
            case 'kita_bisa':
                $count_inp_org = 0;
                $count_inp_program = 0;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://geni.kitabisa.com/kampanye/campaign-list?category_id=22&limit=11&userpage=kategori&offset=0&limit=11');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Accept: application/json',
                    'Referer: https://kitabisa.com/',
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'X-ktbs-api-version: 1.0.0',
                    'X-ktbs-client-version: 1.0.0',
                    'X-ktbs-platform-name: pwa',
                    'X-ktbs-request-id: 7a11dd69-aef5-406b-b2a3-a5c0d2586316',
                    'X-ktbs-signature: 2055b5e8e958647f207a70a2276053330c0454841e02ed650c5539514b3c7b58',
                    'X-ktbs-time: 1752802325'
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Pesan gagal terkirim, error: ' . $err,
                    ]);
                }

                $res = json_decode($response);

                    return response()->json([
                        'status' => 'astaga',
                        'message' => $res,
                    ]);
                    //adasd

                $count_inp_org = 0;
                $count_inp_program = 0;

                $headers = ['accept: application/json', 'referer: https://kitabisa.com/', 'x-ktbs-api-version: 1.0.0', 'x-ktbs-client-name: kanvas', 'x-ktbs-client-version: 1.0.0', 'x-ktbs-platform-name: pwa', 'x-ktbs-request-id: 7a11dd69-aef5-406b-b2a3-a5c0d2586316', 'x-ktbs-signature: 2055b5e8e958647f207a70a2276053330c0454841e02ed650c5539514b3c7b58', 'x-ktbs-time: 1752802325', 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'];

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'https://geni.kitabisa.com/kampanye/campaign-list?' . (isset($category_id) ? 'category_id=' . $category_id : '') . '&limit=' . $data_count . '&userpage=kategori&offset=0');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt_array($curl, [
                    CURLOPT_HTTPHEADER => $headers,
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Pesan gagal terkirim, error: ' . $err,
                    ]);
                }

                $res = json_decode($response);

                return response()->json([
                    'status' => 'failed',
                    'message' => $res,
                ]);

                foreach ($data as $index => $item) {
                    $organization_id = null;

                    if (isset($item->slug)) {
                        $org = GrabOrganization::where('name', $item->campaigner->name)->first();

                        if (!$org) {
                            $org_curl = curl_init();
                            curl_setopt($org_curl, CURLOPT_URL, 'https://core.bantutetangga.com/public/campaigner/' . $item->campaigner->slug);
                            curl_setopt($org_curl, CURLOPT_RETURNTRANSFER, 1);
                            $org_response = curl_exec($org_curl);
                            $org_err = curl_error($org_curl);
                            curl_close($org_curl);

                            if (!$org_err) {
                                $org_data = json_decode($org_response);

                                if (isset($org_data->data)) {
                                    $org_info = $org_data->data;
                                    $desc = isset($org_info->bio) ? $this->removeEmoji($org_info->bio) : null;

                                    $new_org = new GrabOrganization();
                                    $new_org->user_id = $org_info->id;
                                    $new_org->platform_id = $platform_id;
                                    $new_org->name = $org_info->name;
                                    $new_org->avatar = $org_info->logo;
                                    $new_org->description = $desc;

                                    $new_org->is_affiliated = \App\Models\Organization::whereJsonContains('alias_names', $org->name)->exists() ? 1 : 0;

                                    $new_org->save();
                                    $count_inp_org++;
                                    $organization_id = $new_org->id;
                                }
                            }
                        } else {
                            $organization_id = $org->id;
                        }
                    }

                    if ($organization_id) {
                        $program = \App\Models\GrabProgram::where('grab_organization_id', $organization_id)->where('slug', $item->slug)->first();

                        if ($program === null) {
                            try {
                                $new_program = new \App\Models\GrabProgram();
                                $new_program->user_id = $organization_id;
                                $new_program->platform_id = $platform_id;
                                $new_program->grab_organization_id = $organization_id;
                                $new_program->id_grab = $index ?? null;
                                $new_program->name = $item->title;
                                $new_program->slug = $item->slug;
                                $new_program->permalink = 'https://bantutetangga.com/campaign/' . $item->slug;
                                $new_program->target_status = $item->status;
                                $new_program->target_amount = $item->target;
                                $new_program->collect_amount = $item->funds;
                                $new_program->over_at = $item->expired_at;
                                $new_program->status_percent = $item->percentage;
                                $new_program->image_url = $item->cover;
                                $new_program->program_created_at = \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                                // Set other fields as needed

                                $simpan = $new_program->save();
                                $count_inp_program++;
                            } catch (Exception $e) {
                                return response()->json([
                                    'status' => 'failed',
                                    'message' => 'Pesan gagal terkirim, error: ' . $e->getMessage(),
                                ]);
                            }
                        }
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Proses selesai',
                    'data' => [
                        'organisasi_baru' => $count_inp_org,
                        'program_baru' => $count_inp_program,
                    ],
                ]);
        }
    }


    public function grabdoPlatformLeads()
    {
        return view('admin.leads.grabdo_platform.index');
    }

    public function getLeadsPlatformDatatable(Request $request)
    {
        $data = Cache::remember('leads_platforms:all', now()->addDays(3), function () {
            return \App\Models\LeadsPlatform::all();
        });

        return Datatables::of($data)
            ->editColumn('status', function ($row) {
                if ($row->is_active == 1) {
                    return '<span class="badge badge-pill badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-pill badge-danger">Tidak Aktif</span>';
                }
            })
            ->addColumn('program_count', function ($row) {
                return $row->grab_programs->count();
            })
            ->addColumn('action', function ($row) {
                if ($row->is_active) {
                    $btn_edit = '<a href="" target="_blank" class="badge badge-sm badge-primary"><i class="fa fa-list"></i></a>';
                    $btn_grab = '<a style="cursor: pointer;" onClick="openDonaturLoyalModal(`' . $row->name . '`)" class="badge badge-sm badge-success"><i class="fa fa-database"></i></button>';

                    return $btn_edit . ' ' . $btn_grab;
                } else {
                    return '-';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function grabdoPlatform(Request $request)
    {
        $name = $request->platform_name;
        $data_count = $request->data_count;
        $page = $request->page_number;
        $search = $request->title_search;

        return $this->getLeadsDataFromApi($name, $data_count, $page, $search);
    }

    public function updateOrgPhone(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'phone_number' => 'required|string'
        ], [
            'phone_number.required' => 'Nomor telephone harus diisi',
        ]);

        try {
            $organization = GrabOrganization::findOrFail($request->id);
            $organization->phone = $request->phone_number;

            Cache::delete('grab_organization_data');
            $organization->save();

            return response()->json([
                'success' => true,
                'message' => 'No telp berhasil diupdate'
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data no telp' . $err->getMessage()
            ], 500);
        }
    }

    public function select2(Request $request)
    {
        $query = GrabOrganization::query();
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
}
