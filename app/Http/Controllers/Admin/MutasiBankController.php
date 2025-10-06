<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Transaction;

use DataTables;

class MutasiBankController extends Controller
{
    protected $token;
    protected $host;
    public function __construct()
    {
        $this->token       = 'cTN6Unh0NzJmY1FrR0p3Rms2bWJrSnVrVVRVZm1ndWVoUW9pMzZkYzhnRWM0ZXZYYjBYa1VuNkdHa0ZM659c11584f804';
        $this->host        = 'https://mutasibank.co.id/api/v1';

        $this->middleware(function ($request, $next) {
            $user = auth()->user(); // diasumsikan sudah pasti login via middleware('admin')

            if (!$user || $user->position !== 'super_admin') {

                
                return redirect()->route('adm.index')
                    ->with('error', 'Akses khusus Super Admin!');
            }

            return $next($request);
        });
    }

    /**
     * Akun termasuk Saldo
     */
    public function index()
    {
        // return view('admin.chat.index');
        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept'        => 'application/json'
        ])->get($this->host . '/user');

        if ($response->successful()) {
            $res  = $response->json();
            $saldo = $res['data']['saldo'] ?? 0;
            return number_format($saldo, 0, ',', '.');
        }

        // debug / logging
        Log::warning('Mutasi API error', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return response()->json([
            'error' => true,
            'status' => $response->status(),
            'message' => 'Gagal ambil data dari Mutasi API',
            'raw' => $response->body()
        ], 500);
    }

    /**
     * Ambil semua Akun
     */
    public function allAccount()
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept'        => 'application/json'
        ])->get($this->host . '/accounts');

        if ($response->successful()) {
            $res          = $response->json();
            $data         = $res['data'] ?? [];
            $sum_cr       = 0;
            $count_cr     = 0;
            $sum_balance  = 0;
            $data_account = [];

            foreach($data as $d) {
                $id            = $d['id'];
                $unique_id     = $d['unique_id'];
                $bank          = str_replace(['Bank', 'Giro / Bisnis', 'New IBBIZ', 'Corporate V2', 'CUZ'], '', $d['bank']);
                $account_no    = $d['account_no'];
                $balance       = $d['balance'];
                $last_activity = $d['last_bot_activity'];

                $account_statement = $this->accountStatement(request(), $id);

                // echo '<br>'.$bank.' - '.number_format($balance, '0', ',', '.').' - ';
                // echo $account_statement['count_trans'].' - '.$account_statement['sum_cr'].' ('.$account_statement['count_cr'].')';
                // echo ' - '.$last_activity;

                $data_account[] = [
                    'bank'          => $bank,
                    'balance'       => number_format($balance, '0', ',', '.'),
                    'last_activity' => $last_activity,
                    'count_trans'   => number_format($account_statement['count_trans'], 0, ',', '.'),
                    'count_cr'      => number_format($account_statement['count_cr'], 0, ',', '.'),
                    'sum_cr'        => number_format($account_statement['sum_cr'], 0, ',', '.'),
                ];
            
                $sum_cr      += $account_statement['sum_cr'];
                $count_cr    += $account_statement['count_cr'];
                $sum_balance += $balance;
            }

            $transaction = Transaction::select(DB::raw('sum(nominal_final) as sum_trans_success'))
                ->where('status', 'success')
                ->whereDate('created_at', '>=', Carbon::now()->startOfMonth()->toDateString())
                ->whereDate('created_at', '<=', Carbon::now()->toDateString())
                ->first();

            $data_others = [
                'count_cr'       => number_format($count_cr, 0, ',', '.'),
                'sum_cr'         => number_format($sum_cr, 0, ',', '.'),
                'sum_balance'    => number_format($sum_balance, 0, ',', '.'),
                'platform_fee'   => number_format(($sum_cr*5/100), 0, ',', '.'),
                'optimation_fee' => number_format(($sum_cr*10/100), 0, ',', '.'),
                'total_fee'      => number_format(($sum_cr*15/100), 0, ',', '.'),
                'trans_success'  => number_format($transaction->sum_trans_success, 0, ',', '.'),
                'infaq'          => number_format($sum_cr-$transaction->sum_trans_success, 0, ',', '.'),
            ];

            $saldo_user = $this->index();

            // echo '<br><br>Total IN All Bank : '.number_format($sum_cr, 0, ',', '.');
            // echo '<br>Platform Fee 5% : '.number_format(($sum_cr*5/100), 0, ',', '.');
            // echo '<br>Optimation Fee 10% : '.number_format(($sum_cr*10/100), 0, ',', '.');
            // echo '<br>Total Platform 15% : '.number_format(($sum_cr*15/100), 0, ',', '.');
            // echo '<br>Transaksi Tercatat : '.number_format($transaction->sum_trans_success, 0, ',', '.');
            // echo '<br>Infaq Umum (belum tercatat) : '.number_format($sum_cr-$transaction->sum_trans_success, 0, ',', '.');

            // echo '<br><br>Saldo Akhir (kecuali Cash dan Iklan) : '.number_format($sum_balance, 0, ',', '.');

            return view('admin.finance.mutasibank', compact('data_account', 'data_others', 'saldo_user'));
        }

        // debug / logging
        Log::warning('Mutasi API error', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }

    /**
     * Account Statement
     */
    public function accountStatement(Request $request, $id='6082')
    {
        // $from = $request->input('date_from', now()->subDays(7)->format('Y-m-d')); // default 7 hari
        $from = $request->input('date_from', date('Y-m-01')); // default tanggal 1
        $to   = $request->input('date_to', now()->format('Y-m-d'));

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept'        => 'application/json'
        ])->asForm()->post($this->host . '/statements/' . $id, [
            'date_from' => $from,
            'date_to'   => $to,
        ]);

        if ($response->successful()) {
            $res  = $response->json();
            $data = $res['data'] ?? [];

            $r1 = $res['module'].' - '.number_format($res['balance'], 2, ',', '.').' - '.count($data).' transaksi';
            
            // hitung jumlah transaksi DB / CR dan total nominalnya
            $countDB = 0;
            $countCR = 0;
            $sumDB   = 0.0; // total nominal debit
            $sumCR   = 0.0; // total nominal credit

            foreach ($data as $tx) {
                $type = strtoupper(trim($tx['type'] ?? ''));
                // kadang amount berupa string / integer / float
                $amount = isset($tx['amount']) ? (float)$tx['amount'] : 0.0;

                if ($type === 'DB' || $type === 'D') {
                    $countDB++;
                    $sumDB += $amount;
                } elseif ($type === 'CR' || $type === 'C') {
                    $countCR++;
                    $sumCR += $amount;
                } else {
                    // kalau ada type lain/blank, abaikan atau log jika perlu
                }
            }

            $r2 = "Jumlah DB: {$countDB} transaksi (total " . number_format($sumDB, 2, ',', '.') . ")\n";
            $r2 .= "Jumlah CR: {$countCR} transaksi (total " . number_format($sumCR, 2, ',', '.') . ")";

            // tampilkan ringkasan + data mentah (JSON terformat)
            $rawJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            // kembalikan sebagai HTML sederhana supaya enak dibaca di browser
            // return nl2br(e($r1)) . '<br><br>' . nl2br(e($r2)) . '<br><br><pre>' . e($rawJson) . '</pre>';
            return [
                'count_trans'  => number_format(count($data), 0, ',', '.'),
                'balance'      => number_format($res['balance'], 2, ',', '.'),
                'sum_cr'       => $sumCR,
                'count_cr'     => $countCR,
                'sum_db'       => $sumDB,
                'count_db'     => $countDB,
            ];
        } else {
            // debug / logging
            Log::warning('Mutasi API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // return '';
            return response()->json([
                'error' => true,
                'status' => $response->status(),
                'message' => 'Gagal ambil data dari Mutasi API',
                'raw' => $response->body()
            ], 500);
        }
    }
    
























    /**
     * Display a listing of the resource.
     */
    public function campaignList()
    {
        return view('admin.ads.campaign');
    }

    /**
     * Display a listing of the resource.
     */
    public function balanceStatus(Request $request)
    {
        $host             = $this->host.'act_931003154576114?fields=name,account_status,balance,amount_spent&access_token='.$this->token;
        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);
            if(isset($res)) {
                $data1 = array(
                    'name'    => $res->name,
                    'status'  => $res->account_status,
                    'balance' => $res->balance,
                    'total'   => $res->amount_spent
                );
            } else {
                $data1 = array(
                    'name'    => '-',
                    'status'  => 0,
                    'balance' => 0,
                    'total'   => 0
                );
            }
        }

        // account BM4
        $host             = $this->host.'act_597272662321196?fields=name,account_status,balance,amount_spent&access_token='.$this->token;
        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);
            if(isset($res)) {
                $data4 = array(
                    'name'    => $res->name,
                    'status'  => $res->account_status,
                    'balance' => $res->balance,
                    'total'   => $res->amount_spent
                );
            } else {
                $data4 = array(
                    'name'    => '-',
                    'status'  => 0,
                    'balance' => 0,
                    'total'   => 0
                );
            }
        }

        return view('admin.ads.balance', compact('data1', 'data4'));
    }

    

    public function adsNeedAction(Request $request)
    {
        $token           = '&access_token='.$this->token;

        $id              = $request->id;
        if($id==1) {
            $account_id  = 'act_931003154576114';       // List Campaign di BM 1
        } else {
            $account_id  = 'act_597272662321196';       // List Campaign di BM 4
        }

        $dn              = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' 23:59:59 -3 day'));

        // List campaign untuk mendapatkan status masing2 campaign
        $host =  $this->host.$account_id."/campaigns?date_preset=today&period=day&time_increment=1&limit=5000";
        $host .= "&fields=id,name,status&filtering=";
        $host .= urlencode("[{'field':'updated_time','operator':'GREATER_THAN','value':'".$dn."'}]").$token;

        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);

        $campaign         = [];
        $campaign_id      = [];
        $campaign_name    = [];

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->data)) {
                $list_campaign = $res->data;
                // Mengurutkan kampanye berdasarkan nama
                usort($list_campaign, function ($a, $b) {
                    return $a->status <=> $b->status;
                });

                for($i=0; $i<count($list_campaign); $i++) {
                    $campaign[$list_campaign[$i]->id] = $list_campaign[$i]->status;
                    $campaign_id[]                    = $list_campaign[$i]->id;
                    $campaign_name[]                  = $list_campaign[$i]->name;
                }
            }
        }


        // Get Data FB ADS PER ID CAMPAIGN
        $param_time      = 'date_preset=today';
        $param_period    = '&period=day';
        $param_increment = '&time_increment=1';
        $param_limit     = '&limit=5000';
        $param_level     = '&level=campaign';
        $param_field     = '&fields=campaign_id,campaign_name,objective,cost_per_conversion,spend,actions';

        $need_action     = [];
        $others          = [];
        $batch_request   = [];


        for($i=0; $i<count($campaign_id);  $i++) {
            $batch_request[] = [
                'method'       => 'GET',
                'relative_url' => $campaign_id[$i].'/insights?'.$param_time.$param_period.$param_increment.$param_level.$param_limit.$param_field
            ];
        }

        $batches         = array_chunk($batch_request, 50); // Membagi request ke dalam batch karena dibatasi 50 oleh fb ads api
        $batches_result  = [];

        foreach ($batches as $batch) {
            $curl        = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->host);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
                'batch'        => json_encode($batch),
                'access_token' => $this->token
            ]));

            $response    = curl_exec($curl);
            $err         = curl_error($curl);
            curl_close($curl);

            $decoded_response   = json_decode($response, true);
            if (isset($decoded_response['error'])) {
                echo "Error: " . $decoded_response['error']['message'] . "\n";
            } else {
                $batches_result = array_merge($batches_result, $decoded_response);
            }

            usleep(100000); // Jeda 0.1 detik (500 ms) untuk menghindari rate limit
        }

        for($i=0; $i<count($batches_result); $i++) {
            if(isset(json_decode($batches_result[$i]['body'])->data[0])) {
                $data_api = json_decode($batches_result[$i]['body'])->data[0];

                if(isset($data_api->spend)) {
                    $spend        = round($data_api->spend);
                } else {
                    $spend        = 0;
                }

                if(isset($data_api->cost_per_conversion)) {
                    $cpr                = array_filter($data_api->cost_per_conversion, function($cpr_val) {
                                                return $cpr_val->action_type == 'donate_website';
                                            });
                    if(isset($cpr[0]->value)) {
                        $cpr      = round($cpr[0]->value);
                    } else {
                        $cpr      = 0;
                    }
                } else {
                    $cpr          = 0;
                }

                if(isset($data_api->actions)) {
                    if($data_api->objective=='LINK_CLICKS') {
                        $result     = array_filter($data_api->actions, function($result_val) {
                                            return $result_val->action_type == 'link_click';
                                        });
                    } else {
                        $result     = array_filter($data_api->actions, function($result_val) {
                                            return $result_val->action_type == 'offsite_conversion.fb_pixel_custom';
                                        });
                    }

                    if(isset($result)) {
                        if(!empty(array_keys($result))) {
                            $result = round($result[max(array_keys($result))]->value);
                        } else {
                            $result = 0;
                        }
                    } else {
                        $result   = 0;
                    }
                } else {
                    $result       = 0;
                }

                // NOTIF ADS TEAM
                if($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('09:00:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                } elseif($spend>14000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                }  elseif($spend>20000 && $result<1 && strtotime(date('H:i:s'))>strtotime('18:30:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                } elseif($cpr>14000 && strtotime(date('H:i:s'))<=strtotime('09:00:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                } elseif($cpr>16000 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                } elseif($cpr>34000 && strtotime(date('H:i:s'))>strtotime('18:30:00')) {
                    $need_action[] = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];

                } else {
                    $others[]      = ['id'=>$data_api->campaign_id, 'name' => $data_api->campaign_name, 'spend' => $spend, 'result' => $result, 'cpr' => $cpr];
                }
            } else {

            }
        }

        return view('admin.ads.needaction', compact('id', 'account_id', 'need_action', 'others', 'campaign'));
    }

    /**
     * Update Status Campaign
     */
    public function adsNeedActionStatusChange(Request $request)
    {
        $id_campaign = $request->campaign_id;
        $id_account  = $request->account_id;
        $status      = $request->status;

        $host        =  $this->host.$id_campaign;

        $curl        = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "status=".$status."&access_token=".$this->token);

        $response    = curl_exec($curl);
        $err         = curl_error($curl);
        curl_close($curl);

        $res = json_decode($response);
        if($res->success==true) {
            return 'success';
        } else {
            return $response;
        }
    }

    /**
     * Datatables Donate
     */
    public function datatablesCampaign(Request $request)
    {
        // if ($request->ajax()) {
            $q = \App\Models\AdsCampaign::query()
                ->leftJoin('program', function ($join) {
                    // join hanya jika program_id > 0
                    $join->on('program.id', '=', 'ads_campaign.program_id')
                         ->where('ads_campaign.program_id', '>', 0);
                })
                ->select('ads_campaign.*', 'program.title')
                ->orderBy('result', 'DESC');

            // === FILTERS ===
            $q->when($request->filled('name'), fn($qq) =>
                $qq->where('name', 'like', '%'.trim($request->name).'%')
            );

            // BUGFIX: sebelumnya $request->naprogramme -> salah ketik
            $q->when($request->filled('program'), fn($qq) =>
                $qq->where('program.title', 'like', '%'.$request->program.'%')
            );

            // is_active: 0/1
            if ($request->has('is_active')) {
                $q->where('ads_campaign.is_active', (int) $request->is_active);
            }

            // any_program & no_program: mutually exclusive
            if ($request->boolean('any_program')) {
                $q->where('ads_campaign.program_id', '>', 0);
            }
            if ($request->boolean('no_program')) {
                $q->where('ads_campaign.program_id', 0);
            }

            // winning: threshold minimal result (default 50 kalau dikirim 1 tombol)
            $q->when($request->filled('winning'), fn($qq) =>
                $qq->where('ads_campaign.result', '>=', (int) $request->winning)
            );

            // splittest: 7 hari terakhir saat aktif
            if ($request->boolean('splittest')) {
                $q->where('ads_campaign.start_time', '>=', now()->subDays(7)->startOfDay());
            }

            // OPTIONAL tambahkan filter spesifik (biar match input di UI lama)
            $q->when($request->filled('ref_code'), fn($qq) =>
                $qq->where('ref_code', 'like', '%'.$request->ref_code.'%')
            );
            $q->when($request->filled('min_spent'), fn($qq) =>
                $qq->where('spend', '>=', (int) str_replace([',','.'], '', $request->min_spent))
            );

            // === ORDERING ===
            $columns = ['name', 'program.title', 'ads_campaign.start_time', 'spend', 'id']; // map kolom DT
            $orderIdx = (int) $request->input('order.0.column', 0);
            $orderCol = $columns[$orderIdx] ?? 'result';
            $orderDir = $request->input('order.0.dir', 'desc');
            $q->orderBy($orderCol, $orderDir);

            // === COUNT ===
            $count_total = (clone $q)->count();

            // === GLOBAL SEARCH ===
            if ($search = $request->input('search.value')) {
                $san = str_replace([',','.'], '', $search);
                $q->where(function ($s) use ($search, $san) {
                    $s->where('ads_campaign.created_at', 'like', '%'.$search.'%')
                      ->orWhere('name', 'like', '%'.$search.'%')
                      ->orWhere('budget', 'like', '%'.$san.'%')
                      ->orWhere('spend', 'like', '%'.$san.'%')
                      ->orWhere('ref_code', 'like', '%'.$san.'%')
                      ->orWhere('adaccount_id', 'like', '%'.$search.'%');
                });
            }
            $count_filter = (clone $q)->count();

            // === PAGINATION ===
            $pageSize = (int) $request->input('length', 10);
            $start    = (int) $request->input('start', 0);
            $data     = $q->skip($start)->take($pageSize)->get();


            return Datatables::of($data)
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->setOffset($start)
                ->addIndexColumn()
                ->addColumn('program', function($row){
                    // if($row->program_id>0) {
                    //     $program_name = \App\Models\Program::select('title')->where('id', $row->program_id)->first();
                    //     if(isset($program_name->title)) {
                    //         return $program_name->title;
                    //     } else {
                    //         return '<span class="badge badge-pill badge-warning" style="cursor:pointer;">salah set program</span>';
                    //     }
                    // } else {
                    //     return '<span class="badge badge-pill badge-danger" style="cursor:pointer;">belum set program</span>';
                    // }
                    if(is_null($row->title)) {
                        return '<span class="badge badge-pill badge-danger" style="cursor:pointer;">belum set program</span>';
                    } else {
                        return $row->title;
                    }
                })
                ->addColumn('start_time', function($row){
                    if(is_null($row->ref_code) || $row->ref_code=='') {
                        $ref_code = '<span class="badge badge-sm badge-danger">Not Set</span>';
                    } else {
                        $ref_code = '<span class="badge badge-sm badge-info">'.$row->ref_code.'</span>';
                    }

                    if($row->adaccount_id=='act_597272662321196') {
                        $bm = '<span class="badge badge-sm badge-info">BM4</span>';
                    } else {
                        $bm = '<span class="badge badge-sm badge-warning">BM1</span>';
                    }

                    return date('Y-m-d H:i', strtotime($row->start_time)).'<br>'.$ref_code.' '.$bm;
                })
                ->addColumn('spend', function($row){
                    return number_format($row->result, 0, ',', '.').'<br>Rp.'.number_format($row->spend, 0, ',', '.');
                })
                ->addColumn('action', function($row){
                    $url_edit  = route('adm.ads.edit', $row->id);
                    $btn = '<a href="'.$url_edit.'" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->rawColumns(['program', 'start_time', 'spend', 'action'])
                ->make(true);
        // }
    }


    /**
     * Display a listing of the resource.
     */
    public function roas(Request $request)
    {
        if(isset($request->ref)) {
            $type_time     = $request->type_time;
            $id_campaign   = $request->ref;
            $ads           = AdsCampaign::select('id', 'ref_code', 'name')->where('id', $id_campaign)->first();
            $name_campaign = $ads->name;
            $ref           = $ads->ref_code;

            $all           = \App\Models\Transaction::select(DB::raw('sum(nominal_final) as sum'), DB::raw('count(id) as count'))
                            ->where('ref_code', $ref);
            $paid          = \App\Models\Transaction::select(DB::raw('sum(nominal_final) as sum'), DB::raw('count(id) as count'))
                            ->where('ref_code', $ref)->where('status', 'success');

            $spent         = 0;
            $result        = 0;

            // Get Data Campaign from FB Ads
            if($request->type_time=='today') {
                $param_time  = 'date_preset=today';
                $all         = $all->where('created_at', 'like', date('Y-m-d').'%')->first();
                $paid        = $paid->where('created_at', 'like', date('Y-m-d').'%')->first();
            } elseif($request->type_time=='day7') {
                $param_time  = 'date_preset=last_7d';
                $all         = $all->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-7 days')).'%')->first();
                $paid        = $paid->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-7 days')).'%')->first();
            } elseif($request->type_time=='day14') {
                $param_time  = 'date_preset=last_14d';
                $all         = $all->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-14 days')).'%')->first();
                $paid        = $paid->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-14 days')).'%')->first();
            }  elseif($request->type_time=='day30') {
                $param_time  = 'date_preset=last_30d';
                $all         = $all->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-30 days')).'%')->first();
                $paid        = $paid->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m-d').'-30 days')).'%')->first();
            } elseif($request->type_time=='monthago') {
                $param_time  = 'date_preset=last_month';
                $all         = $all->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m').'-1 month')).'%')->first();
                $paid        = $paid->where('created_at', '>=', date('Y-m-d', strtotime(date('Y-m').'-1 month')).'%')->first();
            } else {
                $param_time  = 'date_preset=maximum';
                $all         = $all->first();
                $paid        = $paid->first();
            }

            $param_limit     = '&limit=5000';
            $param_level     = '&level=campaign';
            $param_field     = '&fields=campaign_id,campaign_name,objective,cost_per_conversion,spend,actions';
            $url             = $this->host.$id_campaign.'/insights?'.$param_time.$param_level.$param_limit.$param_field.$this->param_token;

            $curl        = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $response    = curl_exec($curl);
            $err         = curl_error($curl);
            curl_close($curl);

            if (!$err) {
                if(isset(json_decode($response)->data[0])) {
                    $data_api   = json_decode($response)->data[0];

                    if(isset($data_api->spend)) {
                        $spent  = round($data_api->spend);
                    }
                }
            }

            if(isset($data_api->actions)) {
                if($data_api->objective=='LINK_CLICKS') {
                    $result_a    = array_filter($data_api->actions, function($result_val) {
                                        return $result_val->action_type == 'link_click';
                                    });
                } else {
                    $result_a    = array_filter($data_api->actions, function($result_val) {
                                        return $result_val->action_type == 'offsite_conversion.fb_pixel_custom';
                                    });
                }

                if(isset($result_a)) {
                    if(!empty(array_keys($result_a))) {
                        $result = round($result_a[max(array_keys($result_a))]->value);
                    }
                }
            }

            if($spent>0 && $result>0) {
                $ads_cpr = round($spent/$result);
            } else {
                $ads_cpr = 0;
            }


            if(isset($all->sum) && $all->sum>0) {
                $all_sum   = $all->sum;
                $all_count = $all->count;
                $all_cpr   = $all->sum/$all->count;
            } else {
                $all_sum   = 0;
                $all_count = 0;
                $all_cpr   = 0;
            }

            if(isset($paid->sum) && $paid->sum>0 && $spent>0) {
                $paid_sum   = $paid->sum;
                $paid_count = $paid->count;
                $paid_cpr   = $paid->sum/$paid->count;
                $roas       = $paid_sum/$spent;
            } else {
                $paid_sum    = 0;
                $paid_count  = 0;
                $paid_cpr    = 0;
                $roas        = 0;
            }

            if($roas>=5){
                $roas = '<span class="badge badge-sm badge-success">'.number_format((float)$roas, 2, ',', '.').'</span>';
            } elseif($roas>=3) {
                $roas = '<span class="badge badge-sm badge-info">'.number_format((float)$roas, 2, ',', '.').'</span>';
            } elseif($roas>=2) {
                $roas = '<span class="badge badge-sm badge-warning">'.number_format((float)$roas, 2, ',', '.').'</span>';
            } else {
                $roas = '<span class="badge badge-sm badge-danger">'.number_format((float)$roas, 2, ',', '.').'</span>';
            }

            // get list content ads
            $ads_data   = $this->getAdsByCampaign($ads->id);
            $ad_content = array();

            foreach ($ads_data['data'] as $vad) {
                $ad_name  = $vad['name'];
                if (isset($vad['adcreatives']['data'][0]['object_story_spec']['video_data']['video_id'])) {
                    $video_id = $vad['adcreatives']['data'][0]['object_story_spec']['video_data']['video_id'];
                    $ad_link  = "https://www.facebook.com/video.php?v=" . $video_id;
                } elseif (isset($ad['adcreatives']['data'][0]['object_story_spec']['photo_data']['image_url'])) {
                    $ad_link  = $vad['adcreatives']['data'][0]['object_story_spec']['photo_data']['image_url'];
                } elseif (isset($ad['adcreatives']['data'][0]['thumbnail_url'])) {
                    $ad_link  = $ad['adcreatives']['data'][0]['thumbnail_url'];
                } else {
                    $ad_link  = '-';
                }

                // get count result
                $ads_detail    = $this->getAdInsights($vad['id']);
                $donate_result = 0;
                $view_video    = 0;
                $view_lp       = 0;
                $click_donate  = 0;
                $payment_info  = 0;
                $form          = 0;
                $test          = 0;
                if (isset($ads_detail['data'][0]['actions'])) {
                    $data_api  = $ads_detail['data'][0]['actions'];
                    foreach ($data_api as $action) {
                        if ($action['action_type'] == 'offsite_conversion.fb_pixel_custom') {
                            $donate_result = $action['value'];
                        }

                        if ($action['action_type'] == 'video_view') {
                            $view_video = $action['value'];
                        }

                        if ($action['action_type'] == 'offsite_conversion.fb_pixel_view_content') {
                            $view_lp = $action['value'];
                        }

                        if ($action['action_type'] == 'offsite_conversion.fb_pixel_lead') {
                            $click_donate = $action['value'];
                        }

                        if ($action['action_type'] == 'offsite_conversion.fb_pixel_add_payment_info') {
                            $payment_info = $action['value'];
                        }

                        if ($action['action_type'] == 'offsite_conversion.fb_pixel_initiate_checkout') {
                            $form = $action['value'];
                        }
                    }
                    $test = $ads_detail['data'][0]['actions'];
                }

                $spend     = 0;
                $cpr       = 0;
                if (isset($ads_detail['data'][0]['spend'])) {
                    $spend = $ads_detail['data'][0]['spend'];

                    if($donate_result>0 && $spend>0) {
                        $cpr = $spend/$donate_result;
                    }
                }

                $ad_content[] = array(
                    'id'           => $vad['id'],
                    'name'         => $ad_name,
                    'result'       => $donate_result,
                    'view_video'   => $view_video,
                    'view_lp'      => $view_lp,
                    'click_donate' => $click_donate,
                    'payment_info' => $payment_info,
                    'form'         => $form,
                    'spend'        => $spend,
                    'cpr'          => $cpr,
                    'link'         => $ad_link,
                    // 'addd'         => $test
                );
            }

            if(count($ad_content)>0) {
                $ad_content = collect($ad_content)->sortByDesc('result')->values()->all();
                // echo '<pre>';
                // print_r($ad_content);
                // echo '</pre>';
                // die('in');
            }

            $data            = array(
                'all_sum'    => $all_sum,
                'all_count'  => $all_count,
                'all_cpr'    => $all_cpr,
                'paid_sum'   => $paid_sum,
                'paid_count' => $paid_count,
                'paid_cpr'   => $paid_cpr,
                'spent'      => $spent,
                'result'     => $result,
                'ads_cpr'    => $ads_cpr,
                'roas'       => $roas,
                'ad_content' => $ad_content
            );

        } else {
            $type_time     = 'all';
            $id_campaign   = '';
            $name_campaign = '';
            $ref           = '';
            $data          = '';
        }
        return view('admin.ads.roas', compact('id_campaign', 'name_campaign', 'ref', 'type_time', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getAdsByCampaign($campaign_id)
    {
        $param_field     = '&fields=id,name,adcreatives{object_story_spec,thumbnail_url}&limit=5000';
        $url             = $this->host.$campaign_id.'/ads?'.$param_field.$this->param_token;

        $curl            = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response        = curl_exec($curl);
        $err             = curl_error($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getAdInsights($ad_id)
    {
        $param_field     = '&fields=ad_id,ad_name,spend,actions&limit=5000';
        $url             = $this->host.$ad_id.'/insights?'.$param_field.$this->param_token;

        $curl            = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response        = curl_exec($curl);
        $err             = curl_error($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $ads = AdsCampaign::select('ads_campaign.*', 'program.title')->leftJoin('program', 'program.id', 'program_id')
                    ->where('ads_campaign.id', $id)->first();

        return view('admin.ads.edit', compact('ads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $data             = AdsCampaign::findOrFail($id);
            $data->program_id = $request->program;
            $data->ref_code   = $request->ref_code;
            $data->is_active  = $request->active;

            // $data->updated_by  = Auth::user()->id;
            $data->updated_at  = date('Y-m-d H:i:s');
            $data->save();

            return redirect()->back()->with('success', 'Berhasil update data campaign');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update, ada kesalahan teknis');
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
     * Datatables Chat
     */
    public function datatablesChat()
    {

    }


    /**
     * Select2 Program
     */
    public function select2(Request $request)
    {
        $query = AdsCampaign::select('id', 'name', 'ref_code');
        $perPage = 10;

        if($request->has('ref_code') && $request->ref_code == 'ada'){
            $query = $query->whereNotNull('ref_code');
        }

        if($request->has('search') && $request->search != ''){
            $query = $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Selalu gunakan paginasi
        $paginator = $query->paginate($perPage);
        $data = $paginator->items();

        $data = collect($data)->map(function ($item) {
            return [
                'id' => (string)$item['id'],
                'ref_code' => $item['ref_code'],
                'name' => $item['name'],
                'text' => $item['name'].' - '.$item['ref_code'] // Tambahkan text untuk Select2
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'pagination' => [
                'more' => $paginator->hasMorePages()
            ]
        ]);
    }

    /**
     * Auto Get New Campaign
     */
    public function getNewCampaign(Request $request)
    {
        $account_id = $request->id == 1
            ? 'act_931003154576114'
            : 'act_597272662321196';

        $host  = rtrim($this->host ?? '', '/');
        $token = $this->token;

        if (!$host || !$token) {
            throw new \RuntimeException('Config FB host/token belum diset di controller.');
        }

        $now = Carbon::now();

        // Sanitizer khusus latin1
        $sanitize = static function ($v) {
            if ($v === null) return null;
            $out = @preg_replace('/[^\x00-\xFF]/u', '', (string) $v);
            return is_string($out) ? $out : (string) $v;
        };

        // =========================
        // 1) FETCH CAMPAIGNS (atribut)
        // NOTE: tambahkan created_time utk fallback start_time
        // =========================
        $endpointCampaigns = "{$host}/{$account_id}/campaigns";
        $campaigns = []; // [campaign_id => data]
        $nextUrl = null;

        $campaignQuery = [
            'limit'        => 5000,
            'fields'       => 'id,name,status,daily_budget,budget_remaining,start_time,updated_time,created_time',
            'access_token' => $token,
        ];

        do {
            $resp = $nextUrl
                ? Http::timeout(60)->retry(3, 500)->get($nextUrl)
                : Http::timeout(60)->retry(3, 500)->get($endpointCampaigns, $campaignQuery);

            if ($resp->failed()) {
                $msg = data_get($resp->json(), 'error.message', 'HTTP '.$resp->status());
                throw new \RuntimeException("Facebook API error (campaigns): {$msg}");
            }

            $json = $resp->json();
            foreach ((array) data_get($json, 'data', []) as $row) {
                $cid = (string) data_get($row, 'id', '');
                if ($cid === '') continue;

                $startTime  = data_get($row, 'start_time');
                $created    = data_get($row, 'created_time');
                $updated    = data_get($row, 'updated_time');

                $campaigns[$cid] = [
                    'id'               => $cid,
                    'program_id'       => 0,
                    'adaccount_id'     => $account_id,
                    'name'             => $sanitize(data_get($row, 'name') ?: null), // <-- SANITIZE
                    'is_active'        => (data_get($row, 'status') === 'ACTIVE') ? 1 : 0,
                    'budget'           => (int) round((int) data_get($row, 'daily_budget', 0)),
                    'start_time'       => $startTime ? Carbon::parse($startTime)->toDateTimeString() : null,
                    'created_time'     => $created ? Carbon::parse($created)->toDateTimeString() : null, // utk fallback
                    'updated_time'     => $updated ? Carbon::parse($updated)->toDateTimeString() : null,
                    'budget_remaining' => data_get($row, 'budget_remaining') !== null
                        ? (int) round((int) $row['budget_remaining'])
                        : 0,
                ];
            }

            $nextUrl = data_get($json, 'paging.next');
        } while (!empty($nextUrl));

        // =========================
        // 2) FETCH INSIGHTS LIFETIME
        // =========================
        $endpointInsights = "{$host}/{$account_id}/insights";
        $lifetimeMap = []; // [campaign_id => ['name','spend','result','cpr']]
        $nextUrl = null;

        $insightsLifetimeQuery = [
            'level'                           => 'campaign',
            'date_preset'                     => 'maximum',
            'time_increment'                  => 'all_days',
            'use_unified_attribution_setting' => true,
            'action_report_time'              => 'impression',
            'fields'                          => 'campaign_id,campaign_name,spend,results,cost_per_result,actions',
            'limit'                           => 5000,
            'access_token'                    => $token,
        ];

        do {
            $resp = $nextUrl
                ? Http::timeout(60)->retry(3, 500)->get($nextUrl)
                : Http::timeout(60)->retry(3, 500)->get($endpointInsights, $insightsLifetimeQuery);

            if ($resp->failed()) {
                $msg = data_get($resp->json(), 'error.message', 'HTTP '.$resp->status());
                throw new \RuntimeException("Facebook API error (insights lifetime): {$msg}");
            }

            $json = $resp->json();
            foreach ((array) data_get($json, 'data', []) as $row) {
                $cid = (string) data_get($row, 'campaign_id', '');
                if ($cid === '') continue;

                $spend   = (float) (is_numeric(data_get($row, 'spend')) ? data_get($row, 'spend') : 0);
                $results = (float) (is_numeric(data_get($row, 'results')) ? data_get($row, 'results') : 0);
                $cpr     = (float) (is_numeric(data_get($row, 'cost_per_result')) ? data_get($row, 'cost_per_result') : 0);

                if ($results == 0 && is_array(data_get($row, 'actions'))) {
                    foreach ($row['actions'] as $a) {
                        if (($a['action_type'] ?? null) === 'link_click' && is_numeric($a['value'] ?? null)) {
                            $results = (float) $a['value'];
                            break;
                        }
                    }
                }
                if ($cpr == 0 && $results > 0) {
                    $cpr = $spend / $results;
                }

                $lifetimeMap[$cid] = [
                    'name'   => $sanitize(trim((string) data_get($row, 'campaign_name', ''))), // <-- SANITIZE
                    'spend'  => $spend,
                    'result' => (int) round($results),
                    'cpr'    => $cpr,
                ];
            }

            $nextUrl = data_get($json, 'paging.next');
        } while (!empty($nextUrl));

        if (empty($campaigns) && empty($lifetimeMap)) {
            echo "Tidak ada data untuk diproses. FINISH";
            return;
        }

        // =========================
        // 3) MERGE & UPSERT (pastikan start_time/updated_time TIDAK NULL)
        // =========================
        $ids = array_values(array_unique(array_merge(array_keys($campaigns), array_keys($lifetimeMap))));
        $existingAll = AdsCampaign::whereIn('id', $ids)->get()->keyBy('id');

        $rows = [];
        $insertCount = 0;
        $updateCount = 0;

        foreach ($ids as $cid) {
            $c = $campaigns[$cid] ?? null;
            $m = $lifetimeMap[$cid] ?? ['name' => '', 'spend' => 0, 'result' => 0, 'cpr' => 0];
            $existing = $existingAll->get($cid);

            // HITUNG INSERT/UPDATE berdasar eksistensi sebelum upsert
            if ($existing) {
                $updateCount++;
            } else {
                $insertCount++;
            }

            // Jika campaign metadata tidak kebaca, fallback dari DB
            if (!$c) {
                $c = [
                    'id'               => $cid,
                    'program_id'       => $existing->program_id ?? 0,
                    'adaccount_id'     => $existing->adaccount_id ?? $account_id,
                    'name'             => $sanitize($existing->name ?? null), // <-- SANITIZE
                    'is_active'        => isset($existing->is_active) ? (int) $existing->is_active : 0,
                    'budget'           => (int) ($existing->budget ?? 0),
                    'start_time'       => $existing?->start_time ? Carbon::parse($existing->start_time)->toDateTimeString() : null,
                    'created_time'     => null,
                    'updated_time'     => $existing?->updated_time ? Carbon::parse($existing->updated_time)->toDateTimeString() : null,
                    'budget_remaining' => (int) ($existing->budget_remaining ?? 0),
                ];
            }

            // Nama wajib ada
            $name = $c['name'] ?: ($m['name'] ?? null);
            if (!$name || $name === '') {
                $name = "untitled-{$cid}";
            }
            $c['name'] = $sanitize($name); // <-- SANITIZE

            // Tentukan start_time (NO NULL)
            $startTime = $c['start_time'] ?? null;
            if (empty($startTime) && !empty($c['created_time'])) {
                $startTime = $c['created_time'];
            }
            if (empty($startTime) && $existing && !empty($existing->start_time)) {
                $startTime = $existing->start_time instanceof \Carbon\Carbon
                    ? $existing->start_time->toDateTimeString()
                    : (string) $existing->start_time;
            }
            if (empty($startTime)) {
                $startTime = $now->toDateTimeString(); // fallback terakhir agar tidak NULL
            }

            // Tentukan updated_time (NO NULL)
            $updatedTime = $c['updated_time'] ?? null;
            if (empty($updatedTime) && $existing && !empty($existing->updated_time)) {
                $updatedTime = $existing->updated_time instanceof \Carbon\Carbon
                    ? $existing->updated_time->toDateTimeString()
                    : (string) $existing->updated_time;
            }
            if (empty($updatedTime)) {
                $updatedTime = $now->toDateTimeString();
            }

            // Gunakan LIFETIME spend/result/cpr, jangan pernah turunkan spend
            $spendLifetime  = (float) ($m['spend'] ?? 0);
            $resultLifetime = (int)   ($m['result'] ?? 0);
            $cprLifetime    = (float) ($m['cpr'] ?? 0);

            if ($existing) {
                $prevSpend = (float) ($existing->spend ?? 0);
                if ($spendLifetime < $prevSpend) {
                    $spendLifetime = $prevSpend;
                }
            }

            // Pertahankan program_id yang sudah terset (>0)
            $programId = 0;
            if ($existing && (int) $existing->program_id > 0) {
                $programId = (int) $existing->program_id;
            } else {
                $programId = (int) ($c['program_id'] ?? 0);
            }

            $rows[] = [
                'id'               => $cid,
                'program_id'       => $programId,
                'adaccount_id'     => (string) ($c['adaccount_id'] ?? $account_id),
                'name'             => (string) $sanitize($c['name']), // <-- SANITIZE (final)
                'is_active'        => (int) ($c['is_active'] ?? 0),
                'budget'           => (int) ($c['budget'] ?? 0),
                'spend'            => (int) round($spendLifetime),
                'cpr'              => (float) $cprLifetime,
                'result'           => (int) $resultLifetime,
                'start_time'       => $startTime,
                'updated_time'     => $updatedTime,
                'budget_remaining' => (int) ($c['budget_remaining'] ?? 0),
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        if (empty($rows)) {
            echo "Tidak ada data untuk di-upsert. FINISH";
            return;
        }

        $updateCols = [
            // 'program_id', // JANGAN update program_id
            'adaccount_id','name','is_active','budget',
            'spend','cpr','result',
            'start_time','updated_time','budget_remaining','updated_at'
        ];

        foreach (array_chunk($rows, 1000) as $chunk) {
            AdsCampaign::upsert($chunk, ['id'], $updateCols);
        }

        echo "FINISH.<br>".count($rows)." campaign diproses <br> Inserted: {$insertCount}, updated: {$updateCount}";
    }


}
