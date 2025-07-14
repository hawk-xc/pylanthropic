<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Str;
use App\Models\Donatur;
use App\Models\Program;
use App\Models\DonaturShortLink;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DonaturController extends Controller
{
    // column projection
    protected $donaturColumn;
    protected $transactionColumn;
    protected $donaturLoyalColumn;

    public function __construct()
    {
        $this->donaturColumn = ['donatur.id', 'donatur.name', 'donatur.telp', 'donatur.want_to_contact', 'donatur.wa_inactive_since', 'donatur.email', 'donatur.password', 'donatur.password_reset', 'donatur.created_at', 'donatur.wa_check', 'donatur.updated_at', 'donatur.last_donate_paid', 'donatur.count_donate_paid', 'donatur.sum_donate_paid', 'donatur.wa_campaign', 'donatur.ref_code', 'donatur.is_muslim', 'donatur.religion'];

        $this->transactionColumn = ['transaction.program_id', 'transaction.donatur_id', 'transaction.payment_type_id', 'transaction.status', 'transaction.nominal', 'transaction.nominal_final', 'transaction.created_at'];

        $this->donaturLoyalColumn = ['donatur_loyal.id', 'donatur_loyal.donatur_id', 'donatur_loyal.program_id', 'donatur_loyal.nominal', 'donatur_loyal.payment_type_id', 'donatur_loyal.desc', 'donatur_loyal.every_period', 'donatur_loyal.every_time', 'donatur_loyal.every_date_period', 'donatur_loyal.every_month_period', 'donatur_loyal.every_date', 'donatur_loyal.every_day', 'donatur_loyal.is_active', 'donatur_loyal.created_at', 'donatur_loyal.created_by', 'donatur_loyal.updated_at', 'donatur_loyal.updated_by'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.donatur.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.donatur.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'telp' => 'required|string',
                'email' => 'required|email|unique:donatur,email',
                'agama' => 'required|in:islam,kristen,katolik,hindu,buddha,konghucu',
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'string' => 'Kolom :attribute harus berupa teks.',
                'email' => 'Format email tidak valid.',
                'unique' => 'Email sudah terdaftar.',
                'in' => 'Kolom :attribute harus salah satu dari: :values.',
            ],
        );

        try {
            $data = new \App\Models\Donatur();
            $data->name = $request->name;
            $data->telp = $request->telp;
            $data->email = $request->email;
            $data->religion = $request->agama;
            $data->is_muslim = $request->agama === 'islam';
            $data->want_to_contact = $request->want_to_contact ? 1 : 0;

            $data->save();

            return redirect()->route('adm.donatur.index')->with('success', 'Donatur berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->route('adm.donatur.index')->with('error', 'Donatur gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $donatur = Donatur::select('*')->where('id', $id)->first();
        $sum_donate_all = \App\Models\Donatur::select($this->donaturColumn)->findOrFail($id)->transaction->sum('nominal_final');
        $sum_donate_paid = \App\Models\Donatur::select($this->donaturColumn)->findOrFail($id)->transaction->where('status', 'success')->sum('nominal_final');
        $count_donate_all = \App\Models\Donatur::select($this->donaturColumn)->findOrFail($id)->transaction->count('id');
        $count_donate_paid = \App\Models\Donatur::select($this->donaturColumn)->findOrFail($id)->transaction->where('status', 'success')->count('id');

        return view('admin.donatur.detail', compact('donatur', 'sum_donate_all', 'sum_donate_paid', 'count_donate_all', 'count_donate_paid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = \App\Models\Donatur::select($this->donaturColumn)->findOrFail($id);

        return view('admin.donatur.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'telp' => 'required|string',
                'email' => 'required|email|unique:donatur,email,' . $id,
                'agama' => 'required|in:islam,kristen,katolik,hindu,buddha,konghucu',
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'string' => 'Kolom :attribute harus berupa teks.',
                'email' => 'Format email tidak valid.',
                'unique' => 'Email sudah terdaftar.',
                'in' => 'Kolom :attribute harus salah satu dari: :values.',
            ],
        );

        try {
            $data = \App\Models\Donatur::findOrFail($id);
            $data->name = $request->name;
            $data->telp = $request->telp;
            $data->email = $request->email;
            $data->religion = $request->agama;
            $data->is_muslim = $request->agama === 'islam';
            $data->want_to_contact = $request->want_to_contact ? 1 : 0;

            $data->save();

            return redirect()->route('adm.donatur.index')->with('success', 'Data Donatur berhasil diupdate.');
        } catch (Exception $e) {
            return redirect()->route('adm.donatur.index')->with('error', 'Data Donatur gagal diupdate.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Datatables Donatur
     */
    // public function datatablesDonatur(Request $request)
    // {
    //     // $data = Donatur::orderBy('count_donate_paid', 'DESC');
    //     $data = Donatur::with(['chat', 'transaction'])->orderBy('count_donate_paid', 'DESC');
    //     if(isset($request->wa_aktif)) {
    //         if($request->wa_aktif==1) {
    //             $data = $data->whereNull('wa_inactive_since');
    //         }
    //     }
    //     if(isset($request->wa_mau)) {
    //         if($request->wa_mau==1) {
    //             $data = $data->where('want_to_contact', '=', 1);
    //         }
    //     }
    //     if(isset($request->muslim)) {
    //         if($request->muslim==1) {
    //             $data = $data->where('is_muslim', '=', 1);
    //         }
    //     }
    //     if(isset($request->sultan)) {
    //         if($request->sultan==500) {
    //             $data = $data->where('sum_donate_paid', '>=', 500000);
    //         } elseif($request->sultan==1000) {
    //             $data = $data->where('sum_donate_paid', '>=', 1000000);
    //         } elseif($request->sultan==2000) {
    //             $data = $data->where('sum_donate_paid', '>=', 2000000);
    //         } elseif($request->sultan==5000) {
    //             $data = $data->where('sum_donate_paid', '>=', 5000000);
    //         }
    //     }
    //     if(isset($request->setia)) {
    //         if($request->setia==1) {
    //             $data = $data->where('count_donate_paid', '>', 2);
    //         }
    //     }
    //     if(isset($request->rutin)) {
    //         if($request->rutin==1) {
    //             $data = $data->where('count_donate_paid', '>', 2);
    //         }
    //     }
    //     if(isset($request->dorman)) {
    //         if($request->dorman==1) {
    //             $data = $data->where('last_donate_paid', '>', date('Y-m-d 00:00:00', strtotime(date('Y-m-d 00:00:00').'-30 days')) );
    //         }
    //     }
    //     $order_column = $request->input('order.0.column');
    //     $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';
    //     $count_total  = $data->count();
    //     $search       = $request->input('search.value');
    //     $count_filter = $count_total;
    //     if($search != ''){
    //         $data     = $data->where(function ($q) use ($search){
    //                     $q->where('name', 'like', '%'.$search.'%')
    //                         ->orWhere('telp', 'like', '%'.$search.'%');
    //                     });
    //         $count_filter = $data->count();
    //     }
    //     $pageSize     = ($request->length) ? $request->length : 10;
    //     $start        = ($request->start) ? $request->start : 0;
    //     $data->skip($start)->take($pageSize);
    //     $data         = $data->get();
    //     return Datatables::of($data)
    //         ->with([
    //             "recordsTotal"    => $count_total,
    //             "recordsFiltered" => $count_filter,
    //         ])
    //         ->setOffset($start)
    //         ->addIndexColumn()
    //         ->addColumn('name', function($row){
    //             if($row->want_to_contact==1) {
    //                 $want_contact = 'Mau';
    //                 $status_color = 'success';
    //             } else {
    //                 $want_contact = 'Belum';
    //                 $status_color = 'warning';
    //             }
    //             if($row->wa_inactive_since===null) {
    //                 $telp = '<span class="badge badge-pill badge-'.$status_color.'">'.$row->telp.' Aktif ('.$want_contact.')</span>';
    //             } else {
    //                 $telp = '<span class="badge badge-pill badge-danger">'.$row->telp.' Not ('.$want_contact.')</span>';
    //             }
    //             return ucwords($row->name).'<br>'.$telp;
    //         })
    //         ->addColumn('last_donate', function($row){
    //             $lastTransaction = $row->transaction->sortByDesc('created_at')->first();
    //             if($lastTransaction) {
    //                 return 'Rp.'.number_format($lastTransaction->nominal_final).' '.
    //                     $lastTransaction->created_at->format('d-m-Y H:i').' ('.$lastTransaction->status.')<br>'.
    //                     ucwords($lastTransaction->program->title ?? '');
    //             }
    //             return 'Belum Pernah';
    //         })
    //         ->addColumn('donate_summary', function($row){
    //             $successTransactions = $row->transaction->where('status', 'success');
    //             $count = $successTransactions->count();
    //             if($count > 0) {
    //                 $sum = number_format($successTransactions->sum('nominal_final'));
    //                 return number_format($count).' kali<br>'.
    //                        '<a href="'.route('adm.donate.perdonatur', $row->id).'" target="_blank" class="badge badge-success">Rp.'.$sum.'</a>';
    //             }
    //             return '0';
    //         })
    //         ->addColumn('chat', function($row){
    //             $lastChat = $row->chat->sortByDesc('created_at')->first();
    //             if($lastChat) {
    //                 $chatTypes = [
    //                     'fu_trans' => 'FU Transaksi',
    //                     'thanks_trans' => 'Setelah Transaksi',
    //                     'repeat_donate' => 'Ajak Transaksi Ulang'
    //                 ];
    //                 $chatType = $chatTypes[$lastChat->type] ?? 'Info Umum';
    //                 return $chatType.'<br>'.$lastChat->created_at->format('d-m-Y H:i');
    //             }
    //             return 'No Data';
    //         })
    //         ->addColumn('action', function($row){
    //             $url_edit  = route('adm.donatur.edit', $row->id);
    //             $actionBtn = '<a href="'.$url_edit.'" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
    //                         <a href="'.route('adm.donatur.show', $row->id).'" class="edit btn btn-info btn-xs mb-1" title="Detail"><i class="fa fa-eye"></i></a>
    //                         ';
    //             return $actionBtn;
    //         })
    //         ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
    //         ->make(true);
    // }

    public function datatablesDonatur(Request $request)
    {
        $data = Donatur::with([
            'chat',
            'transaction' => function ($transaction) {
                $transaction->select($this->transactionColumn);
            },
            'donaturLoyal' => function ($donaturLoyal) {
                $donaturLoyal->select($this->donaturLoyalColumn);
            },
        ])
            ->orderBy('count_donate_paid', 'DESC')
            ->select($this->donaturColumn);

        if (isset($request->wa_aktif)) {
            if ($request->wa_aktif == 1) {
                $data = $data->whereNull('wa_inactive_since');
            }
        }
        if (isset($request->wa_mau)) {
            if ($request->wa_mau == 1) {
                $data = $data->where('want_to_contact', '=', 1);
            }
        }
        if (isset($request->muslim)) {
            if ($request->muslim == 1) {
                $data = $data->where('is_muslim', '=', 1);
            }
        }
        if (isset($request->sultan)) {
            if ($request->sultan == 500) {
                $data = $data->where('sum_donate_paid', '>=', 500000);
            } elseif ($request->sultan == 1000) {
                $data = $data->where('sum_donate_paid', '>=', 1000000);
            } elseif ($request->sultan == 2000) {
                $data = $data->where('sum_donate_paid', '>=', 2000000);
            } elseif ($request->sultan == 5000) {
                $data = $data->where('sum_donate_paid', '>=', 5000000);
            }
        }
        if (isset($request->setia)) {
            if ($request->setia == 1) {
                $data = $data->where('count_donate_paid', '>', 2);
            }
        }
        if (isset($request->rutin)) {
            if ($request->rutin == 1) {
                $data = $data->whereHas('donaturLoyal');
            }
        }
        if (isset($request->dorman)) {
            if ($request->dorman == 1) {
                $data = $data->where('last_donate_paid', '>', date('Y-m-d 00:00:00', strtotime(date('Y-m-d 00:00:00') . '-30 days')));
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';
        $count_total = $data->count();
        $search = $request->input('search.value');
        $count_filter = $count_total;

        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('telp', 'like', '%' . $search . '%');
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
                if ($row->want_to_contact == 1) {
                    $want_contact = 'Mau';
                    $status_color = 'success';
                } else {
                    $want_contact = 'Belum';
                    $status_color = 'warning';
                }

                if ($row->wa_inactive_since === null) {
                    $telp = '<span class="badge badge-pill badge-' . $status_color . '">' . $row->telp . ' Aktif (' . $want_contact . ')</span>';
                } else {
                    $telp = '<span class="badge badge-pill badge-danger">' . $row->telp . ' Not (' . $want_contact . ')</span>';
                }

                // Add count of fixed donations if exists
                $loyalCount = $row->donaturLoyal->count();
                $nameDisplay = ucwords($row->name);
                if ($loyalCount > 0) {
                    $nameDisplay .= ' (' . $loyalCount . ' donasi tetap)';
                }

                return $nameDisplay . '<br>' . $telp;
            })
            ->addColumn('last_donate', function ($row) {
                $lastTransaction = $row->transaction->sortByDesc('created_at')->first();
                if ($lastTransaction) {
                    return 'Rp.' . number_format($lastTransaction->nominal_final) . ' ' . $lastTransaction->created_at->format('d-m-Y H:i') . ' (' . $lastTransaction->status . ')<br>' . ucwords($lastTransaction->program->title ?? '');
                }
                return 'Belum Pernah';
            })
            ->addColumn('donate_summary', function ($row) {
                $successTransactions = $row->transaction->where('status', 'success');
                $count = $successTransactions->count();
                if ($count > 0) {
                    $sum = number_format($successTransactions->sum('nominal_final'));
                    return number_format($count) . ' kali<br>' . '<a href="' . route('adm.donate.perdonatur', $row->id) . '" target="_blank" class="badge badge-success">Rp.' . $sum . '</a>';
                }
                return '0';
            })
            ->addColumn('chat', function ($row) {
                $lastChat = $row->chat->sortByDesc('created_at')->first();
                if ($lastChat) {
                    $chatTypes = [
                        'fu_trans' => 'FU Transaksi',
                        'thanks_trans' => 'Setelah Transaksi',
                        'repeat_donate' => 'Ajak Transaksi Ulang',
                    ];
                    $chatType = $chatTypes[$lastChat->type] ?? 'Info Umum';
                    return $chatType . '<br>' . $lastChat->created_at->format('d-m-Y H:i');
                }
                return 'No Data';
            })
            ->addColumn('action', function ($row) {
                $url_edit = route('adm.donatur.edit', $row->id);

                $actionButton = [
                    'edit' => '<a href="' . $url_edit . '" target="_blank" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>',
                    'show' => '<a href="' . route('adm.donatur.show', $row->id) . '" target="_blank" class="edit btn btn-info btn-xs mb-1" title="Detail"><i class="fa fa-eye"></i></a>',
                    'shortlink' => '<a href="' . route('adm.donatur.shorten-link.index', $row->id) . '?name=' . urlencode($row->name) . '" target="_blank" class="edit btn btn-primary btn-xs mb-1" title="Buat Short Link"><i class="fa fa-link"></i></a>',
                ];

                $actionBtn = $actionButton['edit'] . ' ' . $actionButton['show'] . ' ' . $actionButton['shortlink'];
                return $actionBtn;
            })
            ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
            ->make(true);
    }

    /**
     * Datatables Donatur Dorman
     */
    public function datatablesDonaturDorman(Request $request)
    {
        // if ($request->ajax()) {
        $date_set = date('Y-m-d', strtotime(date('Y-m-d') . '-31 days')) . ' 00:00:00';
        $data = Donatur::where('last_donate_paid', '<=', $date_set)
            // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')
            ->orderBy('count_donate_paid', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('telp', 'like', '%' . $search . '%');
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
                if ($row->want_to_contact == 1) {
                    $want_contact = 'Mau';
                    $status_color = 'success';
                } else {
                    $want_contact = 'Belum';
                    $status_color = 'warning';
                }

                if ($row->wa_inactive_since === null) {
                    $telp = '<span class="badge badge-pill badge-' . $status_color . '">' . $row->telp . ' Aktif (' . $want_contact . ')</span>';
                    if ($row->want_to_contact == 1) {
                        $telp = '<a href="" target="_blank"></a>';
                    }
                } else {
                    $telp = '<span class="badge badge-pill badge-danger">' . $row->telp . ' Not (' . $want_contact . ')</span>';
                }
                return ucwords($row->name) . '<br>' . $telp;
            })
            ->addColumn('last_donate', function ($row) {
                $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                if ($donate_last->count() > 0) {
                    $donate_last = $donate_last->first();
                    $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                    return 'Rp.' . number_format($donate_last->nominal_final) . ' ' . date('d-m-Y H:i', strtotime($donate_last->created_at)) . ' (' . $donate_last->status . ')<br>' . ucwords($program_name->title);
                } else {
                    return 'Belum Pernah';
                }

                return $want_contact . ' ' . $wa_status;
            })
            ->addColumn('donate_summary', function ($row) {
                $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                if ($donate_sum->count() > 0) {
                    $donate_sum_nominal = number_format($donate_sum->count()) . ' kali';
                    return $donate_sum_nominal . '<br><a href="' . route('adm.donate.perdonatur', $row->id) . '" target="_blank" class="badge badge-success" >Rp.' . number_format($donate_sum->sum('nominal_final')) . '</a>';
                } else {
                    return '0';
                }
            })
            ->addColumn('chat', function ($row) {
                $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                if (!empty($chat->type)) {
                    if ($chat->type == 'fu_trans') {
                        $chat_type = 'FU Transaksi';
                    } elseif ($chat->type == 'thanks_trans') {
                        $chat_type = 'Setelah Transaksi';
                    } elseif ($chat->type == 'repeat_donate') {
                        $chat_type = 'Ajak Transaksi Ulang';
                    } else {
                        $chat_type = 'Info Umum';
                    }
                    return $chat_type . '<br>' . date('d-m-Y H:i', strtotime($chat->created_at));
                } else {
                    return 'No Data';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
            ->make(true);
        // }
    }

    /**
     * Datatables Donatur Dorman
     */
    public function datatablesDonaturTetap(Request $request)
    {
        // if ($request->ajax()) {
        $data = Donatur::where('count_donate_paid', '>', '2')
            // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')
            ->orderBy('count_donate_paid', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('telp', 'like', '%' . $search . '%');
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
                if ($row->want_to_contact == 1) {
                    $want_contact = 'Mau';
                    $status_color = 'success';
                } else {
                    $want_contact = 'Belum';
                    $status_color = 'warning';
                }

                if ($row->wa_inactive_since === null) {
                    $telp = '<span class="badge badge-pill badge-' . $status_color . '">' . $row->telp . ' Aktif (' . $want_contact . ')</span>';
                } else {
                    $telp = '<span class="badge badge-pill badge-danger">' . $row->telp . ' Not (' . $want_contact . ')</span>';
                }
                return ucwords($row->name) . '<br>' . $telp;
            })
            ->addColumn('last_donate', function ($row) {
                $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                if ($donate_last->count() > 0) {
                    $donate_last = $donate_last->first();
                    $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                    return 'Rp.' . number_format($donate_last->nominal_final) . ' ' . date('d-m-Y H:i', strtotime($donate_last->created_at)) . ' (' . $donate_last->status . ')<br>' . ucwords($program_name->title);
                } else {
                    return 'Belum Pernah';
                }

                return $want_contact . ' ' . $wa_status;
            })
            ->addColumn('donate_summary', function ($row) {
                $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                if ($donate_sum->count() > 0) {
                    $donate_sum_nominal = number_format($donate_sum->count()) . ' kali';
                    return $donate_sum_nominal . '<br><a href="' . route('adm.donate.perdonatur', $row->id) . '" target="_blank" class="badge badge-success" >Rp.' . number_format($donate_sum->sum('nominal_final')) . '</a>';
                } else {
                    return '0';
                }
            })
            ->addColumn('chat', function ($row) {
                $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                if (!empty($chat->type)) {
                    if ($chat->type == 'fu_trans') {
                        $chat_type = 'FU Transaksi';
                    } elseif ($chat->type == 'thanks_trans') {
                        $chat_type = 'Setelah Transaksi';
                    } elseif ($chat->type == 'repeat_donate') {
                        $chat_type = 'Ajak Transaksi Ulang';
                    } else {
                        $chat_type = 'Info Umum';
                    }
                    return $chat_type . '<br>' . date('d-m-Y H:i', strtotime($chat->created_at));
                } else {
                    return 'No Data';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
            ->make(true);
        // }
    }

    /**
     * Datatables Donatur Sultan = 500ribu keatas total dibayar
     */
    public function datatablesDonaturSultan(Request $request)
    {
        // if ($request->ajax()) {
        $data = Donatur::where('sum_donate_paid', '>=', '500000')
            // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')
            ->orderBy('sum_donate_paid', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('telp', 'like', '%' . $search . '%');
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
                if ($row->want_to_contact == 1) {
                    $want_contact = 'Mau';
                    $status_color = 'success';
                } else {
                    $want_contact = 'Belum';
                    $status_color = 'warning';
                }

                if ($row->wa_inactive_since === null) {
                    $telp = '<span class="badge badge-pill badge-' . $status_color . '">' . $row->telp . ' Aktif (' . $want_contact . ')</span>';
                } else {
                    $telp = '<span class="badge badge-pill badge-danger">' . $row->telp . ' Not (' . $want_contact . ')</span>';
                }
                return ucwords($row->name) . '<br>' . $telp;
            })
            ->addColumn('last_donate', function ($row) {
                $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                if ($donate_last->count() > 0) {
                    $donate_last = $donate_last->first();
                    $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                    return 'Rp.' . number_format($donate_last->nominal_final) . ' ' . date('d-m-Y H:i', strtotime($donate_last->created_at)) . ' (' . $donate_last->status . ')<br>' . ucwords($program_name->title);
                } else {
                    return 'Belum Pernah';
                }

                return $want_contact . ' ' . $wa_status;
            })
            ->addColumn('donate_summary', function ($row) {
                $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                if ($donate_sum->count() > 0) {
                    $donate_sum_nominal = number_format($donate_sum->count()) . ' kali';
                    return $donate_sum_nominal . '<br><a href="' . route('adm.donate.perdonatur', $row->id) . '" target="_blank" class="badge badge-success" >Rp.' . number_format($donate_sum->sum('nominal_final')) . '</a>';
                } else {
                    return '0';
                }
            })
            ->addColumn('chat', function ($row) {
                $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                if (!empty($chat->type)) {
                    if ($chat->type == 'fu_trans') {
                        $chat_type = 'FU Transaksi';
                    } elseif ($chat->type == 'thanks_trans') {
                        $chat_type = 'Setelah Transaksi';
                    } elseif ($chat->type == 'repeat_donate') {
                        $chat_type = 'Ajak Transaksi Ulang';
                    } else {
                        $chat_type = 'Info Umum';
                    }
                    return $chat_type . '<br>' . date('d-m-Y H:i', strtotime($chat->created_at));
                } else {
                    return 'No Data';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
            ->make(true);
        // }
    }

    /**
     * Datatables Donatur Hampir = Pernah transaksi tapi belum pernah dibayar
     */
    public function datatablesDonaturHampir(Request $request)
    {
        // if ($request->ajax()) {
        $data = Donatur::where('sum_donate_paid', '0')
            // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')
            ->orderBy('count_donate_paid', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('telp', 'like', '%' . $search . '%');
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
                if ($row->want_to_contact == 1) {
                    $want_contact = 'Mau';
                    $status_color = 'success';
                } else {
                    $want_contact = 'Belum';
                    $status_color = 'warning';
                }

                if ($row->wa_inactive_since === null) {
                    $telp = '<span class="badge badge-pill badge-' . $status_color . '">' . $row->telp . ' Aktif (' . $want_contact . ')</span>';
                } else {
                    $telp = '<span class="badge badge-pill badge-danger">' . $row->telp . ' Not (' . $want_contact . ')</span>';
                }
                return ucwords($row->name) . '<br>' . $telp;
            })
            ->addColumn('last_donate', function ($row) {
                $donate_last = \App\Models\Transaction::where('donatur_id', $row->id)->orderBy('created_at', 'DESC');
                if ($donate_last->count() > 0) {
                    $donate_last = $donate_last->first();
                    $program_name = \App\Models\Program::where('id', $donate_last->program_id)->first();
                    return 'Rp.' . number_format($donate_last->nominal_final) . ' ' . date('d-m-Y H:i', strtotime($donate_last->created_at)) . ' (' . $donate_last->status . ')<br>' . ucwords($program_name->title);
                } else {
                    return 'Belum Pernah';
                }

                return $want_contact . ' ' . $wa_status;
            })
            ->addColumn('donate_summary', function ($row) {
                $donate_sum = \App\Models\Transaction::where('donatur_id', $row->id)->where('status', 'success');
                if ($donate_sum->count() > 0) {
                    $donate_sum_nominal = number_format($donate_sum->count()) . ' kali';
                    return $donate_sum_nominal . '<br><a href="' . route('adm.donate.perdonatur', $row->id) . '" target="_blank" class="badge badge-success" >Rp.' . number_format($donate_sum->sum('nominal_final')) . '</a>';
                } else {
                    return '0';
                }
            })
            ->addColumn('chat', function ($row) {
                $chat = \App\Models\Chat::where('donatur_id', $row->id)->orderBy('created_at', 'DESC')->first();
                if (!empty($chat->type)) {
                    if ($chat->type == 'fu_trans') {
                        $chat_type = 'FU Transaksi';
                    } elseif ($chat->type == 'thanks_trans') {
                        $chat_type = 'Setelah Transaksi';
                    } elseif ($chat->type == 'repeat_donate') {
                        $chat_type = 'Ajak Transaksi Ulang';
                    } else {
                        $chat_type = 'Info Umum';
                    }
                    return $chat_type . '<br>' . date('d-m-Y H:i', strtotime($chat->created_at));
                } else {
                    return 'No Data';
                }
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-sm">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['name', 'action', 'last_donate', 'donate_summary', 'chat'])
            ->make(true);
        // }
    }

    /**
     * Datatables Program Summary of Donatur
     */
    public function datatablesDonaturProgram(Request $request)
    {
        // if ($request->ajax()) {
        $donatur_id = $request->donatur_id;
        // $data         = \App\Models\Transaction::where('transaction.donatur_id', $donatur_id)
        //                 ->join('program', 'program.id', 'transaction.program_id')
        //                 // ->join('donatur', 'donatur.id', 'transaction.donatur_id')
        //                 ->select('program_id', 'title', 'slug', 'short_desc', 'about', 'approved_at', 'nominal_approved', 'end_date', 'is_publish', 'is_recommended', 'is_urgent', 'is_show_home', 'donate_sum', 'is_islami')
        //                 ->groupBy('transaction.program_id')
        //                 ->orderBy('program.donate_sum', 'DESC');
        $data = DB::table('transaction as t1')
            ->where('t1.donatur_id', $donatur_id)
            ->join('program', 'program.id', 't1.program_id')
            ->select('program_id', 'title', 'slug', 'short_desc', 'about', 'approved_at', 'nominal_approved', 'end_date', 'is_publish', 'is_recommended', 'is_urgent', 'is_show_home', 'donate_sum', 'is_islami')
            ->whereRaw(
                't1.id = (
                                SELECT MAX(t2.id)
                                FROM transaction t2
                                WHERE t2.program_id = t1.program_id
                                AND t2.donatur_id = ' .
                    $donatur_id .
                    '
                            )',
            )
            ->orderBy('program.donate_sum', 'DESC');

        if (isset($request->program_active)) {
            if ($request->program_active == 1) {
                $data = $data->whereNotNull('approved_at')->where('end_date', '>=', date('Y-m-d H:i:s'))->where(
                    'is_publish
                        ',
                    1,
                );
            }
        }

        if (isset($request->program_sum_donate)) {
            if ($request->wa_mau == 5) {
                $data = $data->where('donate_sum', '>=', 5000000);
            } elseif ($request->wa_mau == 10) {
                $data = $data->where('donate_sum', '>=', 10000000);
            } elseif ($request->wa_mau == 20) {
                $data = $data->where('donate_sum', '>=', 20000000);
            } elseif ($request->wa_mau == 50) {
                $data = $data->where('donate_sum', '>=', 50000000);
            } elseif ($request->wa_mau == 10) {
                $data = $data->where('donate_sum', '>=', 100000000);
            }
        }

        if (isset($request->program_islami)) {
            if ($request->program_islami == 1) {
                $data = $data->where('is_islami', '=', 1);
            }
        }

        if (isset($request->trans_time)) {
            $dn = date('Y-m-d');
            if ($request->trans_time > 0) {
                $data = $data->where('transaction.created_at', '>=', date('Y-m-d H:i:s', strtotime($dn . '-' . $request->trans_time . ' days')));
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('program.slug', 'like', '%' . $search . '%')
                    ->orWhere('program.title', 'like', '%' . $search . '%')
                    ->orWhere('program.short_desc', 'like', '%' . $search . '%')
                    ->orWhere('program.about', 'like', '%' . $search . '%');
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
            ->addColumn('title', function ($row) use ($donatur_id) {
                if ($row->is_publish === 1 && $row->end_date > date('Y-m-d H:i:s') && !is_null($row->approved_at)) {
                    $title = $row->title;
                } else {
                    $title = '<span class="text-danger">' . $row->title . '</span>';
                }
                return $title;
            })
            ->addColumn('detail', function ($row) {
                // islami, end date, recommend/urgent/home
                $islami = $row->is_islami == 1 ? '<span class="badge badge-pill badge-sm badge-light">Islami</span>' : '<span class="badge badge-pill badge-sm badge-secondary">not set</span>';

                $end_date = $row->end_date > date('Y-m-d H:i:s') ? '<span class="badge badge-pill badge-sm badge-success">' . $row->end_date . '</span>' : '<span class="badge badge-pill badge-sm badge-secondary">' . $row->end_date . '</span>';

                if ($row->is_recommended == 1) {
                    $publish_status = '<span class="badge badge-pill badge-sm badge-success">Pilihan</span>';
                } elseif ($row->is_urgent == 1) {
                    $publish_status = '<span class="badge badge-pill badge-sm badge-danger">Mendesak</span>';
                } elseif ($row->is_show_home == 1) {
                    $publish_status = '<span class="badge badge-pill badge-sm badge-success">Terbaru</span>';
                } else {
                    $publish_status = '<span class="badge badge-pill badge-sm badge-light">Biasa</span>';
                }

                return $end_date . ' ' . $islami . ' ' . $publish_status;
            })
            ->addColumn('nominal', function ($row) use ($donatur_id) {
                $donatur_nominal_all = \App\Models\Transaction::where('donatur_id', $donatur_id)->where('program_id', $row->program_id)->sum('nominal_final');
                $donatur_nominal_paid = \App\Models\Transaction::where('donatur_id', $donatur_id)->where('program_id', $row->program_id)->where('status', 'success')->sum('nominal_final');
                $donatur_nominal = number_format($donatur_nominal_paid) . '/' . number_format($donatur_nominal_all);
                $program_nominal = number_format($row->donate_sum) . '/' . number_format($row->nominal_approved);
                return $program_nominal . '<br>' . $donatur_nominal;
            })
            ->addColumn('about', function ($row) {
                return substr(strip_tags($row->about), 0, 150) . '...';
            })
            ->addColumn('action', function ($row) {
                $url_edit = route('adm.donatur.edit', $row->program_id);
                $actionBtn =
                    '<a href="' .
                    $url_edit .
                    '" target="_blank" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="' .
                    route('adm.donatur.show', $row->program_id) .
                    '" target="_blank" class="edit btn btn-info btn-xs mb-1" title="Detail"><i class="fa fa-eye"></i></a>
                                ';
                return $actionBtn;
            })
            ->rawColumns(['title', 'detail', 'nominal', 'about', 'action'])
            ->make(true);
        // }
    }

    /**
     * Datatables Donatur Hampir = Pernah transaksi tapi belum pernah dibayar
     */
    public function datatablesDonaturChat(Request $request)
    {
        // if ($request->ajax()) {
        $donatur_id = $request->donatur_id;
        $data = \App\Models\Chat::where('donatur_id', $donatur_id)->orderBy('created_at', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q->where('no_telp', 'like', '%' . $search . '%')
                    ->orWhere('text', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
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
            ->addColumn('type', function ($row) {
                return $row->type;
            })
            ->addColumn('text', function ($row) {
                return $row->text;
            })
            ->addColumn('created_at', function ($row) {
                return date('Y-m-d H:i', strtotime($row->created_at));
            })
            ->addColumn('desc', function ($row) {
                return 'Program | Transaksi';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>';
                return $actionBtn;
            })
            ->rawColumns(['type', 'text', 'created_at', 'desc', 'action'])
            ->make(true);
        // }
    }

    /**
     * Display a listing of the resource.
     */
    public function dorman()
    {
        return view('admin.donatur.dorman');
    }

    /**
     * Display a listing of the resource.
     */
    public function tetap()
    {
        return view('admin.donatur.tetap');
    }

    /**
     * Display a listing of the resource.
     */
    public function sultan()
    {
        return view('admin.donatur.sultan');
    }

    /**
     * Display a listing of the resource.
     */
    public function hampir()
    {
        return view('admin.donatur.hampir');
    }

    /**
     * Update summary donate last_donate_paid, count_donate_paid, sum)donate_paid
     */
    public function donateUpdate()
    {
        // ini hanya untuk pertama kali saja / kalau sudah lama tidak dijalankan fungsi ini
        // agar bisa dijalankan kesemua data donatur
        // $dn      = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'-120 minutes'));
        // $donatur = Donatur::select('id')
        //             ->where(function ($q) use($dn){ $q->whereNull('last_donate_paid')->orWhere('updated_at', '<=', $dn); })
        //             ->orderBy('id','asc')
        //             ->limit(3200)
        //             ->get();

        // agar efisien hanya donatur yg melakukan donasi dibayar 10 hari terakhir saja, meski ada donatur yg akan dijalankan beberapa kali
        $ld = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '-10 days'));
        $last_trans = \App\Models\Transaction::select('donatur_id')->where('status', 'success')->where('created_at', '>=', $ld)->groupBy('donatur_id')->orderBy('donatur_id', 'ASC')->get()->toArray();
        $donatur = Donatur::select('id')->whereIn('id', $last_trans)->orderBy('id', 'asc')->get();

        foreach ($donatur as $v) {
            $trans = \App\Models\Transaction::selectRaw('count(id) as count_donate, sum(nominal_final) as sum_donate, MAX(created_at) as last_transaction')->where('donatur_id', $v->id)->where('status', 'success')->groupBy('donatur_id')->orderBy('created_at', 'DESC')->first();

            Donatur::where('id', $v->id)->update([
                'sum_donate_paid' => isset($trans->sum_donate) ? $trans->sum_donate : 0,
                'count_donate_paid' => isset($trans->count_donate) ? $trans->count_donate : 0,
                'last_donate_paid' => isset($trans->last_transaction) ? $trans->last_transaction : null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        echo 'FINISH LAST DONATE PAID : ' . count($donatur);
    }

    /**
     * Cek WA Dorman
     */
    public function waDorman()
    {
        $data = Donatur::where('last_donate_paid', '<=', '2023-08-11 00:00:00')
            ->where('last_donate_paid', '>=', '2023-03-01 00:00:00')
            // ->where('wa_campaign', '!=', 'dorman-25-08-2023')
            ->whereNull('wa_campaign')
            ->where('want_to_contact', '1')
            ->whereNull('wa_inactive_since')
            ->orderBy('last_donate_paid', 'asc')
            ->limit(4)
            ->get();

        foreach ($data as $v) {
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $trans = \App\Models\Transaction::select('program.id', 'title', 'slug')->join('program', 'program_id', 'program.id')->where('transaction.status', 'success')->where('donatur_id', $v->id)->orderBy('transaction.created_at', 'DESC')->first();

            $chat =
                'Perkenalkan saya Isna dari *Bantubersama*, semoga sehat selalu buat Kak *' .
                ucwords($v->name) .
                '* aamiin..

Program yang Anda donasikan sebelumnya :
*' .
                ucwords($trans->title) .
                '*

Masih terus berjalan hingga hari ini

Yuk kembali kita manfaatkan lagi kesempatan ini untuk terlibat aksi nyata dalam kebaikan.
Melalui link dibawah ini

https://bantubersama.com/' .
                $trans->slug .
                '

Kepedulian kita masih terus dinantikan, oleh mereka yang membutuhkan.';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'token' => env('TOKEN_RWA'),
                'number' => $telp,
                'message' => $chat,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            $now = date('Y-m-d H:i:s');

            if ($response->result == 'true') {
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp' => $telp,
                'text' => $chat,
                'token' => env('TOKEN_RWA'),
                'vendor' => 'RuangWA',
                'url' => 'https://app.ruangwa.id/api/send_message',
                'type' => 'repeat_donate',
                'transaction_id' => null,
                'donatur_id' => $v->id,
                'program_id' => $trans->id,
            ]);
        }

        echo 'FINISH';
    }

    /**
     * Cek WA Dorman
     */
    public function waSummaryDonate()
    {
        $data = Donatur::where('sum_donate_paid', '>', 0)->whereNull('wa_campaign')->where('want_to_contact', '1')->whereNull('wa_inactive_since')->orderBy('sum_donate_paid', 'desc')->limit(4)->get();

        foreach ($data as $v) {
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // belum dibuat logic jika ternyata program sebelumnya sudah berakhir / tidak publish
            $nominal_final = \App\Models\Transaction::select('nominal_final')->where('created_at', '<', '2023-09-01 00:00:00')->where('transaction.status', 'success')->where('donatur_id', $v->id)->sum('nominal_final');

            $chat =
                'Salam peduli, sehat dan bahagia selalu buat Anda

Terima kasih atas donasi yang telah diberikan dan sudah menjadi bagian dari pelopor *Misi Kebaikan Bantubersama.com*

Rekap donasi Anda bulan Agustus 2023 sebesar *Rp.' .
                str_replace(',', '.', number_format($nominal_final)) .
                '*
Semoga jiwa kepedulian dan komitmen membantu sesama terus membersamai Anda

Mari terus lanjutkan langkah positif ini untuk membantu sesama, kepedulian Anda masih terus dinantikan bagi mereka yang membutuhkan.

Yuk donasi kembali dengan klik tautan ini

https://bantubersama.com

Terimakash';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'token' => env('TOKEN_RWA'),
                'number' => $telp,
                'message' => $chat,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            $now = date('Y-m-d H:i:s');

            if ($response->result == 'true') {
                $update = Donatur::select('id')->where('id', $v->id);
                $update->update(['wa_campaign' => 'dorman-25-08-2023']);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp' => $telp,
                'text' => $chat,
                'token' => env('TOKEN_RWA'),
                'vendor' => 'RuangWA',
                'url' => 'https://app.ruangwa.id/api/send_message',
                'type' => 'info',
                'transaction_id' => null,
                'donatur_id' => $v->id,
                'program_id' => null,
            ]);
        }

        echo 'FINISH';
    }

    /**
     * Broadcast to Donatur about specific program
     */
    public function waProgramSpecific(Request $request)
    {
        // $donatur_done = \App\Models\Chat::where('program_id', 33)->where('text', 'like', 'salam%')->groupBy('donatur_id')->pluck('donatur_id');
        // $donatur      = Donatur::whereNotIn('id', $donatur_done)
        //                 // ->where('id', 201)->orWhere('id', 208)              // for testing send to Ulul & Isna
        //                 // ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->get();
        //                 ->where('want_to_contact', '1')->whereNull('wa_inactive_since')->update([
        //                     'wa_campaign' => '-'
        //                 ]);

        // print_r($donatur_done);
        // echo count($donatur).'<br>';
        // foreach($donatur as $k => $v) {
        //     echo $v->name.' | '.$v->telp.'<br>';
        // }
        // die(' finish');

        $campaign = 'palestina';
        $program_id = 33;
        // $program    = \App\Models\Program::->where('id', $program_id)->first();

        $donatur = Donatur::where('wa_campaign', '<>', $campaign)
            ->where('created_at', '<', date('Y-m-d', strtotime(date('Y-m-d') . '-4 day')))
            // ->where('id', 201)->orWhere('id', 208)              // for testing send to Ulul & Isna
            ->where('want_to_contact', '1')
            ->whereNull('wa_inactive_since')
            ->orderBy('id', 'asc')
            ->limit(4)
            ->get();

        foreach ($donatur as $v) {
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            $chat =
                'Salam *' .
                ucwords($v->name) .
                '* Donatur #Bantubersama,
*Darurat kemanusiaan masih berlanjut sampai hari ini di Gaza, Palestina*

Korban telah mencapai 9.277 yang 3.677 merupakan anak-anak meninggal dunia, 2.405 perempuan serta 1.200 anak-anak masih tertimbun reruntuhan.
Kebutuhan mendesak obat-obatan, makanan siap saji, dan bantuan emergency lainnya.

Ayo satukan niat untuk memberi harapan kepada saudara kita di Gaza Palestina dengan klik donasi berikut

https://bantubersama.com/bantupalestina';

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/send_message');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'token' => env('TOKEN_RWA'),
                'number' => $telp,
                'message' => $chat,
                'date' => date('Y-m-d'),
                'time' => date('H:i'),
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            $now = date('Y-m-d H:i:s');

            if ($response->result == 'true') {
                Donatur::select('id')
                    ->where('id', $v->id)
                    ->update([
                        'wa_campaign' => $campaign,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            // insert table chat
            \App\Models\Chat::create([
                'no_telp' => $telp,
                'text' => $chat,
                'token' => env('TOKEN_RWA'),
                'vendor' => 'RuangWA',
                'url' => 'https://app.ruangwa.id/api/send_message',
                'type' => 'info',
                'transaction_id' => null,
                'donatur_id' => $v->id,
                'program_id' => $program_id,
            ]);
        }

        echo 'FINISH';
    }

    /**
     * Cek WA Aktif
     */
    public function talentWACheck()
    {
        $data = Donatur::select('id', 'telp')->whereNull('wa_check')->whereNull('wa_inactive_since')->orderBy('id', 'asc')->limit(4)->get();

        foreach ($data as $v) {
            $telp = str_replace(['-', ' ', '(', ')', '+', '.'], '', $v->telp);
            if (substr($telp, 0, 1) == '0') {
                $telp = '62' . substr($telp, 1, 20);
            } elseif (substr($telp, 0, 2) != '62') {
                $telp = '62' . substr($telp, 0, 20);
            }

            // $token = 'uyrY2vsVrVUcDyMJzGNBMsyABCbdnH2k3vcBQJB7eDQUitd5Y3'; // suitcareer
            // $token = 'eUd6GcqCg4iA49hXuo5dT98CaJGpL1ACMgWjjYevZBVe1r62fU'; // bantubersama
            // $token = 'eQybNY3m1wdwvaiymaid7fxhmmrtdjT6VbATPCscshpB197Fqb'; // bantubersama
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://app.ruangwa.id/api/check_number');
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, [
                'token' => env('TOKEN_RWA'),
                'number' => $telp,
            ]);
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            $now = date('Y-m-d H:i:s');

            if ($response->result == 'true') {
                $update = Donatur::select('id')->where('id', $v->id);
                if ($response->onwhatsapp == 'true') {
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => null]);
                } else {
                    $update->update(['wa_check' => $now, 'wa_inactive_since' => $now]);
                }
            }
        }

        echo 'FINISH';
    }

    /**
     * Select2 Donatur
     */
    public function select2(Request $request)
    {
        $data = Donatur::query()->select('id', 'name', 'email', 'telp');
        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('name', 'like', '%' . $request->search . '%')->orWhere('telp', 'like', '%' . $request->search . '%');
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

    public function donaturShortLink(string $id)
    {
        $donatur = Donatur::findOrFail($id);

        return view('admin.donatur.short-link.index', ['donatur' => $donatur]);
    }

    public function createDonaturShortLink(string $id)
    {
        $donatur = Donatur::findOrFail($id);
        $payment_types = \App\Models\PaymentType::select(['id', 'key', 'name'])->get();

        return view('admin.donatur.short-link.create', ['donatur' => $donatur, 'payment_types' => $payment_types]);
    }

    public function storeDonaturShortLink(Request $request)
    {
        $request->validate(
            [
                'donatur_id' => 'required|numeric',
                'name' => 'required|string',
                'program' => 'required|numeric',
                'payment_type' => 'required|string',
                'amount' => 'required|string',
                'description' => 'string',
                'is_active' => 'string|in:on,off',
            ],
            [
                'donatur_id.required' => 'Kolom Donatur ID wajib diisi.',
                'donatur_id.numeric' => 'Kolom Donatur ID harus berupa angka.',
                'program.required' => 'Kolom Program ID wajib diisi.',
                'program.numeric' => 'Kolom Program ID harus berupa angka.',
                'payment_type.required' => 'Kolom Metode Pembayaran wajib diisi.',
                'payment_type.string' => 'Kolom Metode Pembayaran harus berupa teks.',
                'amount.required' => 'Kolom Jumlah wajib diisi.',
                'amount.string' => 'Kolom Jumlah harus berupa teks.',
                'is_active.string' => 'Kolom Status harus berupa teks.',
                'is_active.in' => 'Kolom Status harus salah satu dari: on, off.',
            ],
        );

        try {
            $donaturShortLink = new DonaturShortLink();
            $donaturShortLink->program_id = $request->program;
            $donaturShortLink->donatur_id = $request->donatur_id;
            $donaturShortLink->name = $request->name;
            $donaturShortLink->is_active = $request->is_active === 'on' ? 1 : 0;
            $donaturShortLink->description = $request->description;
            $donaturShortLink->created_by = auth()->user()->id;

            $donaturShortLink->payment_type = $request->payment_type;

            $donaturShortLink->code = $this->generateUniqueCode(10);

            // save the params
            $donatur = Donatur::find($request->donatur_id);
            $program = Program::find($request->program);
            $amount = (int) str_replace('.', '', $request->amount);

            $donaturShortLink->amount = $amount;

            $donaturShortLink->direct_link = 'https://bantubersama.com/' . $program->slug . '/checkout/' . $amount . '/' . $request->payment_type . '?name=' . urlencode($donatur->name) . '&telp=' . urlencode($donatur->telp);

            $donaturShortLink->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyimpan data.',
                'data' => $donaturShortLink,
                'short_url' => url('/s/u/' . $donaturShortLink->code),
            ]);
        } catch (Exception $err) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'err: ' . $err->getMessage(),
                    'errors' => 'error happen',
                ],
                422,
            );
        }
    }

    public function deleteDonaturShortLink(string $id)
    {
        $shortLinkId = DonaturShortLink::findOrFail($id);

        try {
            $shortLinkId->delete();

            return back()->with('message', [
                'type' => 'success',
                'text' => 'Data Short Link berhasil dihapus!',
            ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'type' => 'error',
                'text' => 'Data Short Link gagal dihapus!',
            ]);
        }
    }

    public function editDonaturShortLink(string $id)
    {
        $donaturShortId = DonaturShortLink::findOrFail($id);
        $payment_types = \App\Models\PaymentType::select(['id', 'key', 'name'])->get();

        return view('admin.donatur.short-link.edit', ['donatur_short_link' => $donaturShortId, 'payment_types' => $payment_types]);
    }

    public function updateDonaturShortLink(string $id, Request $request)
    {
        $request->validate(
            [
                'donatur_id' => 'required|numeric',
                'name' => 'required|string',
                'program' => 'required|numeric',
                'payment_type' => 'required|string',
                'amount' => 'required|string',
                'description' => 'string',
                'is_active' => 'string|in:on,off',
            ],
            [
                'donatur_id.required' => 'Kolom Donatur ID wajib diisi.',
                'donatur_id.numeric' => 'Kolom Donatur ID harus berupa angka.',
                'program.required' => 'Kolom Program ID wajib diisi.',
                'program.numeric' => 'Kolom Program ID harus berupa angka.',
                'payment_type.required' => 'Kolom Metode Pembayaran wajib diisi.',
                'payment_type.string' => 'Kolom Metode Pembayaran harus berupa teks.',
                'amount.required' => 'Kolom Jumlah wajib diisi.',
                'amount.string' => 'Kolom Jumlah harus berupa teks.',
                'is_active.string' => 'Kolom Status harus berupa teks.',
                'is_active.in' => 'Kolom Status harus salah satu dari: on, off.',
            ],
        );

        try {
            $donaturShortLink = DonaturShortLink::findOrFail($id);
            $donaturShortLink->program_id = $request->program;
            $donaturShortLink->donatur_id = $request->donatur_id;
            $donaturShortLink->name = $request->name;
            $donaturShortLink->is_active = $request->is_active === 'on' ? 1 : 0;
            $donaturShortLink->description = $request->description;
            $donaturShortLink->updated_by = auth()->user()->id;

            $donaturShortLink->payment_type = $request->payment_type;

            // save the params
            $donatur = Donatur::find($request->donatur_id);
            $program = Program::find($request->program);
            $amount = (int) str_replace('.', '', $request->amount);

            $donaturShortLink->amount = $amount;

            $donaturShortLink->direct_link = 'https://bantubersama.com/' . $program->slug . '/checkout/' . $amount . '/' . $request->payment_type . '?name=' . urlencode($donatur->name) . '&telp=' . urlencode($donatur->telp);

            $donaturShortLink->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data.',
                'data' => $donaturShortLink,
                'short_url' => url('/s/u/' . $donaturShortLink->code),
            ]);
        } catch (Exception $err) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'err: ' . $err->getMessage(),
                    'errors' => 'error happen',
                ],
                422,
            );
        }
    }

    public function getDonaturShortLink(string $id)
    {
        $query = DonaturShortLink::query();

        $query->where('donatur_id', $id);

        return DataTables::of($query)
            ->addColumn('action', function ($shortLink) {
                $editUrl = '<a href="' . route('adm.donatur.shorten-link.edit', $shortLink->id) . '" class="btn btn-warning btn-xs" title="Edit"><i class="fa fa-edit"></i></a>';

                $deleteUrl = '<form class="d-inline delete-form" action="' . route('adm.donatur.shorten-link.delete', $shortLink->id) . '" method="POST">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-xs delete-btn" title="Delete"><i class="fas fa-trash"></i></button></form>';

                $shortUrl = '<a href="' . url('/s/u/' . $shortLink->code) . '" target="_blank" class="btn btn-primary btn-xs" title="Show"><i class="fas fa-external-link-alt"></i></a>';

                return $editUrl . ' ' . $shortUrl . ' ' . $deleteUrl;
            })
            ->editColumn('is_active', function ($shortLink) {
                return $shortLink->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->editColumn('direct_link', function ($link) {
                return '<a href="' . $link->direct_link . '" target="_blank">' . Str::limit($link->direct_link, 30) . '</a>';
            })
            ->addColumn('short_url_column', function ($shortLink) {
                $shortUrl = url('/s/u/' . $shortLink->code);
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

    protected function generateUniqueCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $existsInShortLinks = \App\Models\ShortLinkModel::where('code', $code)->exists();
            $existsInDonaturLinks = \App\Models\DonaturShortLink::where('code', $code)->exists();

            $attempt++;

            if ($attempt >= $maxAttempts) {
                throw new Exception('Failed to generate unique code after ' . $maxAttempts . ' attempts');
            }
        } while ($existsInShortLinks || $existsInDonaturLinks);

        return $code;
    }
}
