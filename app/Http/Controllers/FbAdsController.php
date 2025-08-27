<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\WaBlastController;

use App\Models\Program;
use App\Models\AdsCampaign;
use App\Models\AdsCampaignHistory;

class FbAdsController extends Controller
{
    protected $token;
    protected $host;
    public function __construct()
    {
        $this->token       = env('TOKEN_FB_DEVELOPER_ADS');
        $this->host        = 'https://graph.facebook.com/v22.0/';

    }

    public function index(Request $request)
    {
        if($request->id==1) {
            $account_id = 'act_931003154576114';   // AdAccount BM 1
        } else {
            $account_id = 'act_597272662321196';   // AdAccount BM 4
        }

        $d_min_3day       = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' 23:59:59 -3 day'));

        $token            = '&access_token='.$this->token;
        $param_time       = 'date_preset=today&period=day&time_increment=1&limit=5000';
        $param_field      = '&fields=id,name,status,budget_remaining,daily_budget,start_time,stop_time,updated_time,insights{spend,cost_per_conversion,frequency,impressions,clicks,cpc,cpm,ctr,reach,actions}';
        $param_filter     = "&filtering=[{'field':'updated_time','operator':'GREATER_THAN','value':'".$d_min_3day."'}]";
        $param_time_range = "&time_range={'since':'".date('Y-m-d')."','until':'".date('Y-m-d')."'}";

        $url              = $this->host.$account_id.'/campaigns?'.$param_time.urlencode($param_filter).$param_time_range.$param_field.$token;

        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->data)) {
                $campaign_not_good = '';
                $data_api          = $res->data;
                for($i=0; $i<count($data_api); $i++) {
                    $spend     = 0;
                    $result    = 0;
                    $cpr       = 0;
                    $is_active = 0;
                    // insert to table history
                    if(isset($data_api[$i]->insights->data)) {
                        $data_api_insight       = $data_api[$i]->insights->data[max(array_keys($data_api[$i]->insights->data))];

                        $data                   = new AdsCampaignHistory;
                        $data->ads_campaign_id  = $data_api[$i]->id;
                        $data->is_active        = ($data_api[$i]->status=='ACTIVE') ? 1 : 0;
                        $is_active              = ($data_api[$i]->status=='ACTIVE') ? 1 : 0;

                        if(isset($data_api_insight->spend)) {
                            $data->spend        = round($data_api_insight->spend);
                            $spend              = round($data_api_insight->spend);
                        } else {
                            $data->spend        = 0;
                            $spend              = 0;
                        }

                        if(isset($data_api_insight->frequency)) {
                            $data->frequency    = round($data_api_insight->frequency);
                        } else {
                            $data->frequency    = 0;
                        }

                        if(isset($data_api_insight->impressions)) {
                            $data->impressions  = round($data_api_insight->impressions);
                        } else {
                            $data->impressions  = 0;
                        }

                        if(isset($data_api_insight->clicks)) {
                            $data->clicks       = round($data_api_insight->clicks);
                        } else {
                            $data->clicks       = 0;
                        }

                        if(isset($data_api_insight->cpc)) {
                            $data->cpc          = round($data_api_insight->cpc);
                        } else {
                            $data->cpc          = 0;
                        }

                        if(isset($data_api_insight->cpm)) {
                            $data->cpm          = round($data_api_insight->cpm);
                        } else {
                            $data->cpm          = 0;
                        }

                        if(isset($data_api_insight->ctr)) {
                            $data->ctr          = round($data_api_insight->ctr);
                        } else {
                            $data->ctr          = 0;
                        }

                        if(isset($data_api_insight->reach)) {
                            $data->reach        = round($data_api_insight->reach);
                        } else {
                            $data->reach        = 0;
                        }

                        if(isset($data_api_insight->cost_per_conversion)) {
                            $cpr                = array_filter($data_api_insight->cost_per_conversion, function($cpr_val) {
                                                        return $cpr_val->action_type == 'donate_website';
                                                    });
                            if(isset($cpr[0]->value)) {
                                $data->cpr      = round($cpr[0]->value);
                                $cpr            = round($cpr[0]->value);
                            } else {
                                $data->cpr      = 0;
                                $cpr            = 0;
                            }
                        } else {
                            $data->cpr          = 0;
                            $cpr                = 0;
                        }

                        if(isset($data_api_insight->actions)) {
                            $result             = array_filter($data_api_insight->actions, function($result_val) {
                                                    return $result_val->action_type == 'offsite_conversion.fb_pixel_custom';
                                                });
                            if(isset($result)) {
                                if(!empty(array_keys($result))) {
                                    $data->result = round($result[max(array_keys($result))]->value);
                                    $result       = round($result[max(array_keys($result))]->value);
                                } else {
                                    $data->result = 0;
                                    $result       = 0;
                                }
                            } else {
                                $data->result     = 0;
                                $result           = 0;
                            }
                        } else {
                            $data->result         = 0;
                            $result               = 0;
                        }

                        $data->save();
                        echo '<br>Success Insert : '.$data_api[$i]->name.' : '.$result.' '.$cpr.' '.$spend;
                    } else {
                        echo '<br>No data insights : '.$data_api[$i]->name;
                    }

                    // Insert table ads_campaign
                    $check_campaign = AdsCampaign::where('id', $data_api[$i]->id)->select('program_id');

                    if($check_campaign->count()>0) {
                        AdsCampaign::where('id', $data_api[$i]->id)->first()->update([
                            'adaccount_id'     => $account_id,
                            'name'             => $data_api[$i]->name,
                            'is_active'        => ($data_api[$i]->status=='ACTIVE') ? 1 : 0,
                            'budget'           => round($data_api[$i]->daily_budget),
                            'spend'            => $spend,
                            'result'           => $result,
                            'cpr'              => $cpr,
                            'updated_time'     => date('Y-m-d H:i:s', strtotime($data_api[$i]->updated_time)),
                            'budget_remaining' => (isset($data_api[$i]->budget_remaining)) ? round($data_api[$i]->budget_remaining) : null,
                            'update_at'        => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        $data                   = new AdsCampaign;
                        $data->id               = $data_api[$i]->id;
                        $data->program_id       = 0;
                        $data->adaccount_id     = $account_id;
                        $data->name             = $data_api[$i]->name;
                        $data->is_active        = ($data_api[$i]->status=='ACTIVE') ? 1 : 0;
                        $data->budget           = round($data_api[$i]->daily_budget);
                        $data->spend            = $spend;
                        $data->result           = $result;
                        $data->cpr              = $cpr;
                        $data->start_time       = date('Y-m-d H:i:s', strtotime($data_api[$i]->start_time));
                        $data->updated_time     = date('Y-m-d H:i:s', strtotime($data_api[$i]->updated_time));
                        $data->budget_remaining = (isset($data_api[$i]->budget_remaining)) ? round($data_api[$i]->budget_remaining) : null;
                        $data->save();
                    }

                    // NOTIF ADS TEAM
                    if($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    } elseif($spend>14000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    }  elseif($spend>17000 && $result<1 && strtotime(date('H:i:s'))>strtotime('18:30:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    } elseif($cpr>14000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    } elseif($cpr>16000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    } elseif($cpr>28000 && strtotime(date('H:i:s'))>strtotime('18:30:00') && $is_active==1) {
                        $campaign_not_good .= '
'.$data_api[$i]->name.' : '.number_format($result).' '.number_format($cpr).' '.number_format($spend);
                    
                    }
                } // END FOR
                echo '<br><br>'.$campaign_not_good;
                echo '<br><br>FINISH';
            } else {
                echo '<br>No Data Campaigns';
            }
        }
    }

    /**
     * Insert to Ads Campaign History
     */
    public function detailPerCampaign(Request $request)
    {
        $token           = '&access_token='.$token;
        $token           = '&access_token='.$this->token;

        $param_time      = 'date_preset=today';
        $param_period    = '&period=day';
        $param_increment = '&time_increment=1';
        $param_limit     = '&limit=5000';
        $param_level     = '&level=campaign';
        $param_field     = '&fields=campaign_name,objective,cost_per_conversion,spend,frequency,impressions,clicks,cpc,cpm,ctr,reach,actions';

        if($request->id==1) {
            $account_id  = 'act_931003154576114';       // List Campaign di BM 1
        } else {
            $account_id  = 'act_597272662321196';       // List Campaign di BM 4
        }

        $dn              = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' 23:59:59 -3 day'));
        $list_campaign   = AdsCampaign::where('adaccount_id', $account_id)->where('updated_time', '>=', $dn)->select('id')->get();
        foreach ($list_campaign as $key => $v) {

            $url         = $this->host.$v->id.'/insights?'.$param_time.$param_period.$param_increment.$param_level.$param_limit.$param_field.$token;

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

                    $data                   = new AdsCampaignHistory;
                    $data->ads_campaign_id  = $v->id;

                    if(isset($data_api->spend)) {
                        $data->spend        = round($data_api->spend);
                    } else {
                        $data->spend        = 0;
                    }

                    if(isset($data_api->frequency)) {
                        $data->frequency    = round($data_api->frequency);
                    } else {
                        $data->frequency    = 0;
                    }

                    if(isset($data_api->impressions)) {
                        $data->impressions  = round($data_api->impressions);
                    } else {
                        $data->impressions  = 0;
                    }

                    if(isset($data_api->clicks)) {
                        $data->clicks       = round($data_api->clicks);
                    } else {
                        $data->clicks       = 0;
                    }

                    if(isset($data_api->cpc)) {
                        $data->cpc          = round($data_api->cpc);
                    } else {
                        $data->cpc          = 0;
                    }

                    if(isset($data_api->cpm)) {
                        $data->cpm          = round($data_api->cpm);
                    } else {
                        $data->cpm          = 0;
                    }

                    if(isset($data_api->ctr)) {
                        $data->ctr          = round($data_api->ctr);
                    } else {
                        $data->ctr          = 0;
                    }

                    if(isset($data_api->reach)) {
                        $data->reach        = round($data_api->reach);
                    } else {
                        $data->reach        = 0;
                    }

                    if(isset($data_api->cost_per_conversion)) {
                        $cpr                = array_filter($data_api->cost_per_conversion, function($cpr_val) {
                                                    return $cpr_val->action_type == 'donate_website';
                                                });
                        if(isset($cpr[0]->value)) {
                            $data->cpr      = round($cpr[0]->value);
                        } else {
                            $data->cpr      = 0;
                        }
                    } else {
                        $data->cpr          = 0;
                    }

                    if(isset($data_api->actions)) {
                        $result             = array_filter($data_api->actions, function($result_val) {
                                                return $result_val->action_type == 'offsite_conversion.fb_pixel_custom';
                                            });
                        if(isset($result)) {
                            if(!empty(array_keys($result))) {
                                $data->result = round($result[max(array_keys($result))]->value);
                            } else {
                                $data->result = 0;
                            }
                        } else {
                            $data->result   = 0;
                        }
                    } else {
                        $data->result       = 0;
                    }

                    $data->save();
                    
                    echo '<br>success';
                } else {
                    echo '<br>no data';
                }
            }
        }

        echo '<br>FINISH';
    }


    // buatkan 1 function induk yaitu autoOnOffRule() yg isinya ambil data semua per campaign dan cek apakah perlu di off autoRulesOff() / on autoRulesOn(), sehingga cronjob cukup panggil function autoOnOffRule() saja
    public function autoOnOffRule(Request $request)
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

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->data)) {
                $list_campaign = $res->data;

                // Filter kamapnye yang aktif saja
                $active_campaigns = array_filter($list_campaign, function ($campaign) {
                    return $campaign->status === 'ACTIVE';
                });

                // Mengurutkan kampanye berdasarkan nama
                usort($active_campaigns, function ($a, $b) {
                    return $a->status <=> $b->status;
                });

                for($i=0; $i<count($active_campaigns); $i++) {
                    $campaign[] = ['id'=>$active_campaigns[$i]->id, 'name'=>$active_campaigns[$i]->name, 'status'=>$active_campaigns[$i]->status];
                }
            }


            // Get Data FB ADS PER ID CAMPAIGN
            $param_time      = 'date_preset=today&period=day';
            $param_limit     = '&limit=5000&time_increment=1';
            $param_level     = '&level=campaign';
            $param_field     = '&fields=campaign_id,campaign_name,objective,cost_per_conversion,spend,actions';
            $need_action     = [];

            for($i=0; $i<count($campaign);  $i++) {
                $url         = $this->host.$campaign[$i]['id'].'/insights?'.$param_time.$param_level.$param_limit.$param_field.$token;

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
                        
                        $tn = strtotime(date('H:i:s'));

                        // ON / OFF executiob
                        
                    }
                }
            }
        }
    }


    /**
     * Auto Rules to OFF Campaign
     */
    public function autoRulesOff(Request $request)
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

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->data)) {
                $list_campaign = $res->data;

                // Filter kamapnye yang aktif saja
                $active_campaigns = array_filter($list_campaign, function ($campaign) {
                    return $campaign->status === 'ACTIVE';
                });

                // Mengurutkan kampanye berdasarkan nama
                usort($active_campaigns, function ($a, $b) {
                    return $a->status <=> $b->status;
                });

                for($i=0; $i<count($active_campaigns); $i++) {
                    $campaign[] = ['id'=>$active_campaigns[$i]->id, 'name'=>$active_campaigns[$i]->name, 'status'=>$active_campaigns[$i]->status];
                }
            }


            // Get Data FB ADS PER ID CAMPAIGN
            $param_time      = 'date_preset=today&period=day';
            $param_limit     = '&limit=5000&time_increment=1';
            $param_level     = '&level=campaign';
            $param_field     = '&fields=campaign_id,campaign_name,objective,cost_per_conversion,spend,actions';
            $need_action     = [];

            for($i=0; $i<count($campaign);  $i++) {
                $url         = $this->host.$campaign[$i]['id'].'/insights?'.$param_time.$param_level.$param_limit.$param_field.$token;

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
                        
                        $tn = strtotime(date('H:i:s'));

                        // TGL BIASA / BUKAN RAMADHAN
                        if(false) {
                            // OFF CAMPAIGN
                            if(str_contains(strtolower($data_api->campaign_name), 'splittest')) {
                                // echo '<br> ini split test';
                            } elseif(str_contains(strtolower($data_api->campaign_name), 'wintraf')) {
                                // echo '<br> ini split test';
                            } elseif(str_contains(strtolower($data_api->campaign_name), '1winning')) {
                                if($spend>1000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('04:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>10000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('07:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>14000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('09:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>19000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>24000 && $result<1 && strtotime(date('H:i:s'))>strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>17000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>25000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>25000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=4 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=1 && $result<3) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>24000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=3 && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=5 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=7 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>30000 && strtotime(date('H:i:s'))>strtotime('18:30:00') && $result<3) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && strtotime(date('H:i:s'))>strtotime('18:30:00') && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>22000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>24000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:00:00') && $result<20) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($tn>=strtotime('23:30:00') && $tn<=strtotime('23:59:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } else {
                                    // Good Campaign
                                }
                            } elseif(str_contains(strtolower($data_api->campaign_name), 'winning')) { // ini harusnya gas winning
                                if($spend>1000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('04:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('07:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>16000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('09:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>20000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('12:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>24000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>34000 && $result<1 && strtotime(date('H:i:s'))>strtotime('18:45:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>24000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>28000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>34000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=6 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>24000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=4 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=3 && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>34000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=5 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=7 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>34000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=3 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>28000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:00:00') && $result<20) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($tn>=strtotime('23:30:00') && $tn<=strtotime('23:59:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } else {
                                    // Good Campaign
                                }
                            } else {
                                if($spend>1000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('04:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('07:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>16000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('09:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>20000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('12:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>24000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>34000 && $result<1 && strtotime(date('H:i:s'))>strtotime('18:45:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>17000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>29000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=6 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=4 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>24000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>28000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=3 && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=5 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))<=strtotime('18:30:00') && $result>=7 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>34000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=3 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>28000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>34000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:30:00') && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:00:00') && $result<20) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($tn>=strtotime('23:30:00') && $tn<=strtotime('23:59:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } else {
                                    // Good Campaign
                                }
                            } // END IF OFF CAMPAIGN

                        } else {  // TGL BAGUS / RAMADHAN
                            // OFF CAMPAIGN
                            if(str_contains(strtolower($data_api->campaign_name), 'splittest')) {
                                // echo '<br> ini split test';
                            } elseif(str_contains(strtolower($data_api->campaign_name), 'wintraf')) {
                                // echo '<br> ini split test';
                            } elseif(str_contains(strtolower($data_api->campaign_name), 'winning')) { // ini harusnya gas winning
                                // Tidak ada hasil leads
                                if($spend>2000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('02:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('07:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>16000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('11:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>20000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('15:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>24000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>34000 && $result<1 && strtotime(date('H:i:s'))>strtotime('19:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>29000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=9 && $result<15) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=3 && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>35000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=5 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=7 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>34000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=3 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>43000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>32000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<8) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>44000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<11) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($tn>=strtotime('23:30:00') && $tn<=strtotime('23:59:00')) {
                                    // $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } else {
                                    // Good Campaign
                                }
                            } else {
                                if($spend>2000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('02:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>12000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('07:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>16000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('11:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>20000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('15:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>24000 && $result<1 && strtotime(date('H:i:s'))<=strtotime('18:30:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($spend>34000 && $result<1 && strtotime(date('H:i:s'))>strtotime('19:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>29000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && strtotime(date('H:i:s'))<=strtotime('09:00:00') && $result>=10 && $result<15) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>20000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=4 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && strtotime(date('H:i:s'))<=strtotime('14:00:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($cpr>22000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>26000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>30000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=3 && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>35000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=5 && $result<7) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))<=strtotime('18:00:00') && $result>=7 && $result<10) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>32000 && strtotime(date('H:i:s'))<=strtotime('18:00:00')) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>34000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==1) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>36000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result==2) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>39000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=3 && $result<6) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>43000 && strtotime(date('H:i:s'))>strtotime('18:45:00') && $result>=6 && $result<9) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');

                                } elseif($cpr>32000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<5) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>38000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<8) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } elseif($cpr>44000 && $tn>=strtotime('22:30:00') && $tn<=strtotime('23:50:00') && $result<11) {
                                    $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                
                                } elseif($tn>=strtotime('23:30:00') && $tn<=strtotime('23:59:00')) {
                                    // $this->updateStatusCampaign($data_api->campaign_id, 'PAUSED');
                                } else {
                                    // Good Campaign
                                }
                            } // END IF OFF CAMPAIGN
                        } //END IF TGL BAGUS / RAMADHAN
                    } // END IF ISSET DATA
                } // END IF ISSET RESPONSE
            } // END FOR
        }
        echo "<br>FINISH";
    }


    /**
     * Auto Rules to ON Campaign
     */
    public function autoRulesOn(Request $request)
    {
        if(strtotime(date('H:i:s'))>=strtotime('04:45:00') && strtotime(date('H:i:s'))<=strtotime('22:15:00')) {
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

            if ($err) {
                echo 'Pesan gagal terkirim, error :' . $err;
            } else {
                $res = json_decode($response);

                if(isset($res->data)) {
                    $list_campaign = $res->data;

                    // Filter kamapnye yang tidak aktif saja
                    $active_campaigns = array_filter($list_campaign, function ($campaign) {
                        return $campaign->status === 'PAUSED';
                    });

                    // Mengurutkan kampanye berdasarkan nama
                    usort($active_campaigns, function ($a, $b) {
                        return $a->status <=> $b->status;
                    });

                    for($i=0; $i<count($active_campaigns); $i++) {
                        $campaign[] = ['id'=>$active_campaigns[$i]->id, 'name'=>$active_campaigns[$i]->name, 'status'=>$active_campaigns[$i]->status];
                    }
                }


                // Get Data FB ADS PER ID CAMPAIGN
                $param_time      = 'date_preset=today&period=day';
                $param_limit     = '&limit=5000&time_increment=1';
                $param_level     = '&level=campaign';
                $param_field     = '&fields=campaign_id,campaign_name,objective,cost_per_conversion,spend,actions';
                $need_action     = [];

                for($i=0; $i<count($campaign);  $i++) {
                    $url         = $this->host.$campaign[$i]['id'].'/insights?'.$param_time.$param_level.$param_limit.$param_field.$token;

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

                            $tn = strtotime(date('H:i:s'));
                        
                            // TGL BIASA / BUKAN RAMADHAN
                            if(false) {
                                // ON CAMPAIGN
                                if(str_contains(strtolower($data_api->campaign_name), 'splittest')) {
                                    // echo '<br> ini split test';
                                } elseif(str_contains(strtolower($data_api->campaign_name), 'winning')) {
                                    if($spend>1) {
                                        // mengantisipasi campaign yg memang sudah di off kan agar tidak ikut di ON kan
                                        if($spend<10000 && $result<1 && $tn>=strtotime('04:50:00') && $tn<=strtotime('07:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>=strtotime('05:00:00') && $tn<=strtotime('06:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>strtotime('06:30:00') && $tn<=strtotime('09:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<18000 && $result<1 && $tn>strtotime('09:00:00') && $tn<=strtotime('12:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<20000 && $result<1 && $tn>strtotime('12:30:00') && $tn<=strtotime('14:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<22000 && $result<1 && $tn>strtotime('14:00:00') && $tn<=strtotime('17:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<24000 && $result<1 && $tn>strtotime('14:00:00') && $tn<=strtotime('19:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<33000 && $result<1 && $tn>=strtotime('19:00:00') && $tn<=strtotime('22:50:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<17000 && $tn<=strtotime('09:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<22000 && $tn<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<30000 && $tn<=strtotime('09:00:00') && $result>=6 && $result<20) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<21000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<23000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=4 && $result<27) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<23000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=1 && $result<3) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<26000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<29000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=5 && $result<27) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        
                                        } elseif($cpr<32000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<34000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<36000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<39000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=5 && $result<7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<42000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } else {
                                            // Bad Campaign
                                        }
                                    }
                                } elseif(str_contains(strtolower($data_api->campaign_name), 'luaran')) {  // pihak luar main aman

                                } else {    // internal tapi belum winning
                                    if($spend>1) {
                                        // mengantisipasi campaign yg memang sudah di off kan agar tidak ikut di ON kan
                                        if($spend<10000 && $result<1 && $tn>=strtotime('04:50:00') && $tn<=strtotime('07:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>=strtotime('05:00:00') && $tn<=strtotime('06:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>strtotime('06:30:00') && $tn<=strtotime('09:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<18000 && $result<1 && $tn>strtotime('09:00:00') && $tn<=strtotime('12:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<20000 && $result<1 && $tn>strtotime('12:30:00') && $tn<=strtotime('14:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<22000 && $result<1 && $tn>strtotime('14:00:00') && $tn<=strtotime('17:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<24000 && $result<1 && $tn>strtotime('14:00:00') && $tn<=strtotime('19:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<33000 && $result<1 && $tn>=strtotime('19:00:00') && $tn<=strtotime('22:50:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<17000 && $tn<=strtotime('09:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<22000 && $tn<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<30000 && $tn<=strtotime('09:00:00') && $result>=6 && $result<20) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<21000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<23000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn>strtotime('9:00:00') && $tn<=strtotime('14:00:00') && $result>=4 && $result<27) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<23000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=1 && $result<3) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<26000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<29000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:30:00') && $result>=5 && $result<27) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        
                                        } elseif($cpr<32000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<34000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<36000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<39000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=5 && $result<7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<42000 && $tn>strtotime('19:00:00') && $tn<strtotime('22:30:00') && $result>=7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } else {
                                            // Bad Campaign
                                        }
                                    }
                                } // END IF
                            } else {  // TGL BAGUS / RAMADHAN
                                // ON CAMPAIGN
                                if(str_contains(strtolower($data_api->campaign_name), 'splittest')) {
                                    // echo '<br> ini split test';
                                } elseif(str_contains(strtolower($data_api->campaign_name), 'winning')) {
                                    if($spend>1) {
                                        // mengantisipasi campaign yg memang sudah di off kan agar tidak ikut di ON kan
                                        if($spend<10000 && $result<1 && $tn>=strtotime('02:30:00') && $tn<=strtotime('04:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>=strtotime('04:30:00') && $tn<=strtotime('08:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<16000 && $result<1 && $tn>strtotime('06:30:00') && $tn<=strtotime('08:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<18000 && $result<1 && $tn>strtotime('09:00:00') && $tn<=strtotime('12:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<20000 && $result<1 && $tn>strtotime('12:00:00') && $tn<=strtotime('15:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<22000 && $result<1 && $tn>strtotime('15:00:00') && $tn<=strtotime('17:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<24000 && $result<1 && $tn>strtotime('15:00:00') && $tn<=strtotime('19:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<33000 && $result<1 && $tn>=strtotime('19:00:00') && $tn<=strtotime('22:50:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<19000 && $tn<=strtotime('09:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<35000 && $tn<=strtotime('09:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn<=strtotime('09:00:00') && $result>=9 && $result<15) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<19000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<31000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<37000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<21000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<29000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<34000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=5 && $result<7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=7 && $result<10) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        
                                        } elseif($cpr<33000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<35000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=3 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<42000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<45000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=10) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } else {
                                            // Bad Campaign
                                        }
                                    }
                                } elseif(str_contains(strtolower($data_api->campaign_name), 'luaran')) {  // pihak luar main aman

                                } else {    // internal tapi belum winning
                                    if($spend>1) {
                                        // mengantisipasi campaign yg memang sudah di off kan agar tidak ikut di ON kan
                                        if($spend<10000 && $result<1 && $tn>=strtotime('02:30:00') && $tn<=strtotime('04:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<14000 && $result<1 && $tn>=strtotime('04:30:00') && $tn<=strtotime('08:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<16000 && $result<1 && $tn>strtotime('06:30:00') && $tn<=strtotime('08:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<18000 && $result<1 && $tn>strtotime('09:00:00') && $tn<=strtotime('12:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<20000 && $result<1 && $tn>strtotime('12:00:00') && $tn<=strtotime('15:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<22000 && $result<1 && $tn>strtotime('15:00:00') && $tn<=strtotime('17:30:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<24000 && $result<1 && $tn>strtotime('15:00:00') && $tn<=strtotime('19:00:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($spend<33000 && $result<1 && $tn>=strtotime('19:00:00') && $tn<=strtotime('22:50:00')) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<19000 && $tn<=strtotime('09:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn<=strtotime('09:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<28000 && $tn<=strtotime('09:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<35000 && $tn<=strtotime('09:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn<=strtotime('09:00:00') && $result>=9 && $result<15) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<19000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=2 && $result<4) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<31000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=4 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<37000 && $tn>strtotime('09:00:00') && $tn<=strtotime('14:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } elseif($cpr<21000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<25000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<29000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=3 && $result<5) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<34000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=5 && $result<7) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn>strtotime('14:00:00') && $tn<=strtotime('18:00:00') && $result>=7 && $result<10) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        
                                        } elseif($cpr<33000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result==1) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<35000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result==2) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<38000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=3 && $result<6) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<42000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=6 && $result<9) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');
                                        } elseif($cpr<45000 && $tn>strtotime('18:45:00') && $tn<strtotime('23:00:00') && $result>=10) {
                                            $this->updateStatusCampaign($data_api->campaign_id, 'ACTIVE');

                                        } else {
                                            // Bad Campaign
                                        }
                                    }
                                } // END IF
                            } // END IF TGL BIASA / BAGUS
                        } // END IF ISSET DATA
                    } // END IF ISSET RESPONSE
                } // END FOR
            }
        } // END IF Diatas jam 4:45

        echo "<br>FINISH";
    }



    /**
     * Auto Get Spend Per Campaign
     */
    public function getSpend(Request $request)
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
                $url         = $this->host.$v->id.'/insights?'.$param_time.$param_level.$param_limit.$param_field.$token;

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

                        // INSERT TO TABLE SPEND
                        // Ambil dulu campaign ini pemiliknya program_id apa
                        $adscampaign = AdsCampaign::select('id', 'program_id')->where('id', $data_api->campaign_id)->first();
                        if(!isset($adscampaign->program_id)) {
                            $program_id = 0;
                        } elseif($adscampaign->program_id<1) {
                            $program_id = 0;
                        } else {
                            $program_id = $adscampaign->program_id;
                        }

                        if(isset($adscampaign->id)) {    // campaign sudah ada di tabel
                            $ads_campaign_id = $adscampaign->id;
                        } else {                        // campaign belum ada di table, maka insert baru
                            $data                   = new AdsCampaign;
                            $data->id               = $data_api->campaign_id;
                            $data->program_id       = $program_id;
                            $data->adaccount_id     = $account_id;
                            $data->name             = $data_api->campaign_name;
                            $data->is_active        = 1;
                            $data->budget           = (isset($v->daily_budget)) ? round($v->daily_budget) : 0;
                            $data->spend            = $spend;
                            $data->cpr              = 0;
                            $data->result           = 0;
                            $data->start_time       = date('Y-m-d H:i:s', strtotime($v->start_time));
                            $data->updated_time     = date('Y-m-d H:i:s', strtotime($v->updated_time));
                            $data->budget_remaining = (isset($v->budget_remaining)) ? round($v->budget_remaining) : null;
                            $data->save();
                            $ads_campaign_id        = $data->id;
                        }

                        // kemudian insert to spend program langsung? hanya yg spend nya ada yg artinya campaignnya aktif
                        $check_spent  = \App\Models\ProgramSpend::where('program_id', $program_id)->where('date_request', $dn)->where('type', 'ads')
                                        ->where('ads_campaign_id', $ads_campaign_id)->select('id')->first();
                        $spend_number = str_replace('.', '', $spend);
                        if($spend>0 && !isset($check_spent->id)) {
                            $data                   = new \App\Models\ProgramSpend;
                            $data->program_id       = $program_id;
                            $data->title            = trim($data_api->campaign_name);
                            $data->nominal_request  = $spend_number;
                            $data->nominal_approved = $spend_number + ($spend_number*11/100);
                            $data->date_request     = $dn;
                            $data->date_approved    = $dn;
                            $data->approved_by      = 1;
                            $data->type             = 'ads';
                            $data->is_operational   = 1;
                            $data->status           = 'done';
                            $data->desc_request     = trim($data_api->campaign_name);
                            $data->ads_campaign_id  = $ads_campaign_id;
                            $data->save();
                        } else {
                            // echo 'NOT : '.$program_id.' - '.$data_api->campaign_name.' - '.$spend.'<br>';
                            // tidak perlu insert karena tidak ada spent yg artinya campaign ini off seharian kemarin
                        }
                        // echo $data_api->campaign_name.' = '.$spend.'<br>';
                    } // END IF ISSET DATA ADS PEr Campaign
                } // END IF ISSET RESPONSE
            } // END FOR
        }
        echo "<br>FINISH";
    }


    /**
     * Update Status Campaign
     */
    public function updateStatusCampaign($id_campaign, $status)
    {
        $host  =  $this->host.$id_campaign;

        $curl        = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "status=".$status."&access_token=".$this->token);

        $response    = curl_exec($curl);
        $err         = curl_error($curl);
        curl_close($curl);

        $res = json_decode($response);
        // if($res->success==true) {
        //     return 'success';
        // } else {
        //     return $response;
        // }
    }



    /**
     * Format phone number of the resource.
     */
    public function sendThanksWA($trans_id='', $program_id='', $donatur_id='', $nominal_final='')
    {
        $program = Program::where('id', $program_id)->first();
        $donatur = Donatur::where('id', $donatur_id)->first();
        $chat    = 'Terimakasih dermawan *'.ucwords(trim($donatur->name)).'*.
Kebaikan Anda sangat berarti bagi kami yang membutuhkan, semoga mendapat balasan yang lebih berarti. Aamiin.
Atas Donasi :
*'.ucwords($program->title).'*
Sebesar : *Rp '.str_replace(',', '.', number_format($nominal_final)).'*';

            (new WaBlastController)->sentWA($donatur->telp, $chat, 'thanks_trans', $trans_id, $donatur->id, $program->id);
    }

    /**
     * GET /ads/campaigns/update-program
     *
     * Query (opsional):
     * - limit   : default 10, max 10 (jumlah campaign diproses)
     * - after   : cursor (id campaign terakhir dari respons sebelumnya)
     * - pace_ms : jeda antar panggilan API (ms), default 300
     *
     * Hasil: JSON {checked, mapped, items, unmatched, next_after, error}
     */
    public function updateProgramAdsCampaign(Request $request)
    {
        $token   = $this->token;              // dari __construct()
        $host    = rtrim($this->host, '/').'/';
        $limit   = min((int) $request->get('limit', 80), 80);
        $after   = $request->get('after');    // cursor: campaign id terakhir
        $paceMs  = max(0, (int) $request->get('pace_ms', 300));
        $onlyAcct = 'act_597272662321196';    // fokus ke account ini dulu

        $checked  = 0;
        $mapped   = 0;
        $items    = [];
        $unmatched = [];
        $lastId   = null;

        // Ambil campaign yang belum punya program_id untuk adaccount tertentu
        $query = AdsCampaign::query()
            ->select(['id','program_id','updated_time','adaccount_id'])
            ->where(function ($q) {
                $q->whereNull('program_id')->orWhere('program_id', 0);
            })
            ->where('adaccount_id', $onlyAcct)
            ->orderByDesc('updated_time');

        // Cursor sederhana pakai id (string numeric)
        if (!empty($after)) {
            $query->where('id', '<', $after);
        }

        $campaigns = $query->limit($limit)->get();

        foreach ($campaigns as $c) {
            $campId = (string) $c->id;
            $lastId = $campId;
            $checked++;

            // --- Ambil 1 ad saja per campaign (cukup satu konten) ---
            $adsRes = \Http::withToken($token)->get($host.$campId.'/ads', [
                'limit'  => 1,
                'fields' => 'id,account_id,creative{id,object_story_spec,effective_object_story_id},effective_object_story_id',
            ]);

            if (!$adsRes->ok()) {
                $unmatched[] = [
                    'campaign_id' => $campId,
                    'reason'      => 'Facebook API error '.$adsRes->status().': '.$adsRes->body(),
                    'endpoint'    => $host.$campId.'/ads',
                ];
                $this->sleepPaced($paceMs);
                continue;
            }

            $ads = (array) $adsRes->json('data', []);
            if (count($ads) === 0) {
                $unmatched[] = [
                    'campaign_id' => $campId,
                    'reason'      => 'Tidak ada ad ditemukan'
                ];
                $this->sleepPaced($paceMs);
                continue;
            }

            $ad         = $ads[0];
            $adId       = (string) data_get($ad, 'id');
            $ownerAccId = 'act_'.data_get($ad, 'account_id');
            $creative   = (array) data_get($ad, 'creative', []);
            $storyId    = (string) (data_get($ad, 'effective_object_story_id') ?: data_get($creative, 'effective_object_story_id'));

            // Pastikan ads memang milik account yang diinginkan
            if ($ownerAccId !== $onlyAcct) {
                $unmatched[] = [
                    'campaign_id'       => $campId,
                    'ad_id'             => $adId,
                    'reason'            => 'Ad milik account lain',
                    'owner_account_id'  => $ownerAccId,
                ];
                $this->sleepPaced($paceMs);
                continue;
            }

            // --- 1) Coba dari creative.object_story_spec (CTA/link) ---
            $found = $this->extractUrlFromCreative($creative);

            // --- 2) Kalau belum ada, coba dari story (page post) ---
            if (!$found && $storyId) {
                $found = $this->extractUrlFromStory($host, $token, $storyId);
            }

            // --- 3) Kalau masih belum ada, parse dari ad previews HTML ---
            if (!$found && $adId) {
                $found = $this->extractUrlFromPreview($host, $token, $adId, $ownerAccId);
            }

            if (!$found) {
                $row = [
                    'campaign_id'      => $campId,
                    'ad_id'            => $adId,
                    'creative_id'      => (string) data_get($creative, 'id'),
                    'reason'           => 'URL tidak terdeteksi pada creative (object_story_spec kosong / ad preview & story tidak mengandung link LP).',
                    'owner_account_id' => $ownerAccId,
                ];
                if ($storyId) $row['story_candidates'] = [$storyId];
                $unmatched[] = $row;
                $this->sleepPaced($paceMs);
                continue;
            }

            $rawUrl   = $found['url'] ?? null;
            $source   = $found['source'] ?? 'unknown';
            $finalUrl = $this->cleanUrl($rawUrl); // buang query ?ref=...

            // Ambil slug (path penuh tanpa leading slash)
            $slug = $this->slugFromUrl($finalUrl);

            // Cocokkan ke tabel program
            $matchedProgramId = null;
            if ($slug) {
                $prog = \DB::table('program')
                    ->select('id','slug')
                    ->where('slug', $slug)
                    ->orWhereRaw('LOWER(slug) = ?', [strtolower($slug)])
                    ->first();

                if ($prog) {
                    $matchedProgramId = (int) $prog->id;

                    // Update program_id campaign ini
                    AdsCampaign::where('id', $campId)->update([
                        'program_id' => $matchedProgramId,
                        // 'lp_url'     => $finalUrl,
                        'updated_at' => now(),
                    ]);
                    $mapped++;
                }
            }

            $items[] = [
                'campaign_id'        => $campId,
                'ad_id'              => $adId,
                'creative_id'        => (string) data_get($creative, 'id'),
                'raw_url'            => $rawUrl,
                'clean_url'          => $finalUrl,
                'slug'               => $slug,
                'matched_program_id' => $matchedProgramId,
                'source'             => $source,
                'owner_account_id'   => $ownerAccId,
            ];

            $this->sleepPaced($paceMs);
        }

        return response()->json([
            'checked'    => $checked,
            'mapped'     => $mapped,
            'items'      => $items,
            'unmatched'  => $unmatched,
            'next_after' => $lastId ?: null,
            'error'      => null,
        ]);
    }

    /** Jeda antar request supaya nggak kebentur rate limit */
    protected function sleepPaced(int $ms): void
    {
        if ($ms > 0) usleep($ms * 1000);
    }

    /** Parser URL dari creative.object_story_spec (video/link/carousel) */
    protected function extractUrlFromCreative(array $creative): ?array
    {
        $oss = (array) data_get($creative, 'object_story_spec', []);
        $cands = [];

        // Video + CTA
        if ($u = data_get($oss, 'video_data.call_to_action.value.link')) $cands[] = $u;

        // Link ads
        if ($u = data_get($oss, 'link_data.link')) $cands[] = $u;

        // Child attachments (carousel style)
        $children = (array) data_get($oss, 'link_data.child_attachments', []);
        foreach ($children as $ca) {
            if ($u = data_get($ca, 'link')) $cands[] = $u;
        }

        // Generic CTA at root
        if ($u = data_get($oss, 'call_to_action.value.link')) $cands[] = $u;

        foreach ($cands as $u) {
            $u = $this->ensureLandingUrl($u);
            if ($u && preg_match('~^https?://bantubersama\.com/[\w\-/]+~i', $u)) {
                return ['url' => $u, 'source' => 'object_story_spec'];
            }
        }
        return null;
    }

    /** Fallback dari story (FB page post): ambil CTA, attachments, link, hingga URL di caption */
    protected function extractUrlFromStory(string $host, string $token, string $storyId): ?array
    {
        $res = \Http::withToken($token)->get($host.$storyId, [
            'fields' => 'permalink_url,link,message,call_to_action{type,value},attachments{subattachments,media_type,type,url,unshimmed_url,target{url}}',
        ]);
        if (!$res->ok()) return null;

        $j = $res->json();
        $cands = [];

        // 1) CTA di post (paling akurat untuk objective=PURCHASE video)
        if ($u = data_get($j, 'call_to_action.value.link')) $cands[] = $u;

        // 2) Field link langsung
        if ($u = data_get($j, 'link')) $cands[] = $u;

        // 3) Attachments dan sub-attachments
        $atts = (array) data_get($j, 'attachments.data', []);
        foreach ($atts as $att) {
            foreach (['unshimmed_url', 'target.url', 'url'] as $k) {
                if ($u = data_get($att, $k)) $cands[] = $u;
            }
            $subs = (array) data_get($att, 'subattachments.data', []);
            foreach ($subs as $sa) {
                foreach (['unshimmed_url', 'target.url', 'url'] as $k) {
                    if ($u = data_get($sa, $k)) $cands[] = $u;
                }
            }
        }

        // 4) URL yang ditulis di caption/message
        if ($msg = data_get($j, 'message')) {
            foreach ($this->extractUrlsFromText($msg) as $u) {
                $cands[] = $u;
            }
        }

        foreach ($cands as $u) {
            $u = $this->ensureLandingUrl($u);
            if ($u && preg_match('~^https?://bantubersama\.com/[\w\-/]+~i', $u)) {
                return ['url' => $u, 'source' => "story:$storyId"];
            }
        }
        return null;
    }

    /** Fallback dari ad preview HTML: ambil semua href/data-lynx-uri dan decode l.php?u= */
    protected function extractUrlFromPreview(string $host, string $token, string $adId, string $accountId): ?array
    {
        $formats = ['DESKTOP_FEED_STANDARD', 'MOBILE_FEED_STANDARD', 'INSTAGRAM_STANDARD'];
        foreach ($formats as $fmt) {
            $res = \Http::withToken($token)->get($host.$adId.'/previews', [
                'ad_format'    => $fmt,
                'render_type'  => 'FALLBACK',
                'account_id'   => $accountId,
            ]);
            if (!$res->ok()) continue;

            $html = (string) ($res->json('data.0.body') ?? '');
            if ($html === '') continue;

            $links = [];
            if (preg_match_all('~(?:href|data-lynx-uri)="([^"]+)"~i', $html, $m)) {
                $links = array_merge($links, $m[1]);
            }
            if (preg_match_all('~https?://[^\s"<>]+~i', $html, $m2)) {
                $links = array_merge($links, $m2[0]);
            }

            foreach (array_unique($links) as $u) {
                $u = $this->ensureLandingUrl($u);
                if ($u && preg_match('~^https?://bantubersama\.com/[\w\-/]+~i', $u)) {
                    return ['url' => $u, 'source' => "preview:$fmt"];
                }
            }

            // jeda antar format supaya aman rate limit
            usleep(120000);
        }
        return null;
    }

    /** Ekstrak semua URL dari teks/caption */
    protected function extractUrlsFromText(string $text): array
    {
        $out = [];
        if (preg_match_all('~https?://[^\s"<>]+~i', $text, $m)) {
            foreach ($m[0] as $u) {
                $u = $this->ensureLandingUrl($u);
                if ($u) $out[] = $u;
            }
        }
        return array_values(array_unique($out));
    }

    /** Decode link shim, entity, dan backslash */
    protected function ensureLandingUrl(string $u): ?string
    {
        $u = html_entity_decode($u, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // l.facebook.com redirect
        if (preg_match('~^https?://l\.facebook\.com/l\.php\?~i', $u)) {
            $parts = parse_url($u);
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $q);
                if (!empty($q['u'])) {
                    $u = urldecode($q['u']);
                }
            }
        }

        // Strip escaping backslash pada preview JSON/HTML
        $u = stripcslashes($u);

        return $u ?: null;
    }

    /** Buang query string; sisakan scheme + host + path */
    protected function cleanUrl(?string $url): ?string
    {
        if (!$url) return null;
        $parts = parse_url($url);
        if (!$parts || empty($parts['scheme']) || empty($parts['host'])) return null;

        $path = $parts['path'] ?? '';
        return $parts['scheme'].'://'.$parts['host'].$path;
    }

    /** Ambil slug (path tanpa leading slash) dari URL */
    protected function slugFromUrl(?string $url): ?string
    {
        if (!$url) return null;
        $parts = parse_url($url);
        $path  = isset($parts['path']) ? ltrim($parts['path'], '/') : '';
        return $path !== '' ? $path : null;
    }

}
