<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Models\AdsCampaign;
use App\Models\AdsCampaignHistory;

use DataTables;

class AdsController extends Controller
{
    protected $token;
    protected $host;
    public function __construct()
    {
        $this->token       = 'EAAFlv11kLJkBOyx9yFonwCdNAwKV6rWZC0nna2gZBOegnPjZB5HxhY9ubM6dIDhTi37fiOJXmyfV6YQO3ZCJFgUB1ebpgyuVub6VU56kucJjGCu9j5lIW66lYDznXMfLBl2CM5Rsfx4kl0A8AvOZBXVGzWROxrIxZBcuv31ta27jOPUI7TcB8yySFCb3jKMiCymQZBzEQwY';
        $this->host        = 'https://graph.facebook.com/v19.0/';
        $this->param_token = '&access_token='.$this->token;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.chat.index');
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

    /**
     * Display a listing of the resource.
     */
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
        // $list_campaign   = AdsCampaign::where('adaccount_id', $account_id)->where('updated_time', '>=', $dn)->select('id')->get();

        for($i=0; $i<count($campaign_id);  $i++) {
        // foreach($list_campaign as $key => $v) {
            $url         = $this->host.$campaign_id[$i].'/insights?'.$param_time.$param_period.$param_increment.$param_level.$param_limit.$param_field.$token;

            $curl        = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $response    = curl_exec($curl);
            $err         = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo 'Pesan gagal terkirim, error :' . $err;
            } else {
                if(isset(json_decode($response)->data[0])) {
                    $data_api = json_decode($response)->data[0];

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
                    $others[]      = ['id'=>$campaign_id[$i], 'name' => $campaign_name[$i], 'spend' => 0, 'result' => 0, 'cpr' => 0];
                }
            }  // END IF
        }   // END FOREACH

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
            $data = \App\Models\AdsCampaign::
                    // leftJoin('program', 'program.id', 'program_id')
                    // ->select('ads_campaign.*', 'title')->
                    orderBy('result', 'DESC');

            if(isset($request->name)) {
                $data = $data->where('name', 'like', '%'.trim($request->name).'%');
            }

            if(isset($request->program)) {
                $data = $data->where('program_id', $request->naprogramme);
            }

            if(isset($request->is_active)) {
                $data = $data->where('ads_campaign.is_active', $request->is_active);
            }

            $order_column = $request->input('order.0.column');
            $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

            $count_total  = $data->count();

            $search       = $request->input('search.value');

            $count_filter = $count_total;
            if($search != ''){
                $data     = $data->where(function ($q) use ($search){
                            $q->where('ads_campaign.created_at', 'like', '%'.$search.'%')
                                ->orWhere('name', 'like', '%'.$search.'%')
                                ->orWhere('budget', 'like', '%'.str_replace([',', '.'], '', $search).'%')
                                ->orWhere('adaccount_id', 'like', '%'.$search.'%');
                            });
                $count_filter = $data->count();
            }

            $pageSize     = ($request->length) ? $request->length : 10;
            $start        = ($request->start) ? $request->start : 0;

            $data->skip($start)->take($pageSize);

            $data         = $data->get();
            return Datatables::of($data)
                ->with([
                    "recordsTotal"    => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->setOffset($start)
                ->addIndexColumn()
                ->addColumn('program', function($row){
                    if($row->program_id>0) {
                        $program_name = \App\Models\Program::select('title')->where('id', $row->program_id)->first();
                        if(isset($program_name->title)) {
                            return $program_name->title;
                        } else {
                            return '<span class="badge badge-pill badge-warning" style="cursor:pointer;">salah set program</span>';
                        }
                    } else {
                        return '<span class="badge badge-pill badge-danger" style="cursor:pointer;">belum set program</span>';
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
        $data      = AdsCampaign::select('id', 'name', 'ref_code');
        $last_page = null;

        if($request->has('ref_code') && $request->ref_code == 'ada'){
            $data = $data->whereNotNull('ref_code');
        }

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

        $data = $data->get();
        $data = $data->map(function ($data) {
            return [
                'id'       => $data->id.'',
                'ref_code' => $data->ref_code,
                'name'     => $data->name
            ];
        });

        return response()->json([
            'status'     => 'success',
            'message'    => 'Data Fetched',
            'data'       => $data,
            'extra_data' => [
                'last_page' => $last_page,
            ]
        ]);
    }

    /**
     * Auto Get New Campaign
     */
    public function getNewCampaign(Request $request)
    {
        $token           = '&access_token='.$this->token;

        $id              = $request->id;
        if($id==1) {
            $account_id  = 'act_931003154576114';       // List Campaign di BM 1
        } else {
            $account_id  = 'act_597272662321196';       // List Campaign di BM 4
        }

        if(isset($request->date)) {
            $dn          = date('Y-m-d H:i:s', strtotime($request->date.' 23:59:59'));
            $param_time  = 'period=day&time_range='.json_encode(array('since' => $request->date, 'until' => $request->date));
        } else {
            $dn          = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' 23:59:59 -1 day'));
            $param_time  = 'date_preset=yesterday&period=day';
        }

        // List campaign untuk mendapatkan status masing2 campaign
        $host =  $this->host.$account_id."/campaigns?date_preset=yesterday&period=day&time_increment=1&limit=5000";
        $host .= "&fields=id,name,status,daily_budget,budget_remaining,start_time,updated_time&filtering=";
        $host .= urlencode("[{'field':'updated_time','operator':'GREATER_THAN','value':'".$dn."'}]").$token;

        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);

        $campaign         = [];

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res           = json_decode($response);
            $list_campaign = $res->data;

            // Get Data FB ADS PER ID CAMPAIGN
            $param_limit     = '&limit=5000&time_increment=1';
            $param_level     = '&level=campaign';
            $param_field     = '&fields=campaign_id,spend,campaign_name';

            foreach ($list_campaign as $key => $v) {
                if ($err) {
                    echo 'Pesan gagal terkirim, error :' . $err;
                } else {
                    $adscampaign = AdsCampaign::select('id')->where('id', $v->id)->first();
                    if(isset($adscampaign->id)) {    // campaign sudah ada di tabel
                        // jadi tidak perlu di insert
                    } else {                        // campaign belum ada di table, maka insert baru
                        $data                   = new AdsCampaign;
                        $data->id               = $v->id;
                        $data->program_id       = 0;
                        $data->adaccount_id     = $account_id;
                        $data->name             = $v->name;
                        $data->is_active        = 1;
                        $data->budget           = round($v->daily_budget);
                        $data->spend            = 0;
                        $data->cpr              = 0;
                        $data->result           = 0;
                        $data->start_time       = date('Y-m-d H:i:s', strtotime($v->start_time));
                        $data->updated_time     = date('Y-m-d H:i:s', strtotime($v->updated_time));
                        $data->budget_remaining = (isset($v->budget_remaining)) ? round($v->budget_remaining) : null;
                        $data->save();
                    }
                } // END IF ISSET RESPONSE
            } // END FOR
        }
        echo "<br>FINISH";
    }
}
