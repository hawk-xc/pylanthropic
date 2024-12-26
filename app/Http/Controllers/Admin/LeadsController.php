<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\WaBlastController;

use DataTables;

class LeadsController extends Controller
{
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
        return view('admin.leads.grab');
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
                'name'         => 'required|string'
            ]);

            $check_org = DB::table('grab_organization')->select('id')->where('name', $request->name)->first();

            if(!isset($check_org->id)) {
                DB::table('grab_organization')->insert([
                    'name'        => $request->name,
                    'phone'       => $request->phone,
                    'address'     => $request->address,
                    'email'       => $request->email,
                    'instagram'   => $request->ig,
                    'facebook'    => $request->fb,
                    'youtube'     => $request->yt,
                    'twitter'     => $request->tw,
                    'fb_pixel'    => $request->pixel,
                    'gtm'         => $request->gtm,
                    'description' => $request->desc,
                    'user_id'     => date('ymdhis')
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
        $id_program     = $request->id;
        $status         = $request->status;
        $type           = $request->type;
        $sc             = 'style="cursor:pointer"';

        $leads_program  = DB::table('grab_program')->select('name')->where('id', $id_program)->first();

        if(isset($leads_program->name)) {
            // update data grab_program
            if($type == 'interest') {
                DB::table('grab_program')->select('id')->where('id', $id_program)->update(['is_interest'=> $status]);
                if($status==1) {
                    $btn = '<span class="badge badge-sm badge-success" '.$sc.' onclick="setInterest('.$id_program.', 0)">Menarik</span>';
                } else {
                    $btn = '<span class="badge badge-sm badge-info" '.$sc.' onclick="setInterest('.$id_program.', 1)">Menarik?</span>';
                }
            } else {
                DB::table('grab_program')->select('id')->where('id', $id_program)->update(['is_taken'=> $status]);
                if($status==1) {
                    $btn = '<span class="badge badge-sm badge-success" '.$sc.' onclick="setTaken('.$id_program.', 0)">Tergarap</span>';
                } else {
                    $btn = '<span class="badge badge-sm badge-info" '.$sc.' onclick="setTaken('.$id_program.', 1)">Garap?</span>';
                }
            }

            return array(
                'status'=>'success',
                'name'  => $leads_program->name,
                'btn'   => $btn
            );
        } else {
            return array('status'=>'fail');
        }
    }

    /**
     * Datatables Chat
     */
    public function grabDatatables(Request $request)
    {
        //$sub_query = DB::table('grab_program')->select('grab_program.*')->groupBy('grab_program.id');
        //$programs  = DB::table(DB::raw("({$sub_query->toSql()}) as grab_program"))->mergeBindings($sub_query)
        
        $data         = DB::table('grab_program')
        //->select('grab_program.*', 'grab_organization.name as lembaga', 'twitter', 'instagram', 'facebook', 'youtube', 'email', 'phone', 'platform')
        //->join('grab_organization', 'grab_organization.user_id', '=', 'grab_program.user_id')
        ->whereNotNull('grab_program.user_id')
        ->orderBy('grab_program.created_at', 'DESC');

        if(isset($request->interest)) {
            if($request->interest==1) {
                $data = $data->where('grab_program.is_interest', 1);
            }
        }

        if(isset($request->taken)) {
            if($request->taken==1) {
                $data = $data->where('grab_program.is_taken', 1);
            }
        }

        if(isset($request->jt20_ar)) {
            if($request->jt20_ar==1) {
                $data = $data->where('grab_program.collect_amount', '<=', 20000000);
            }
        }

        if(isset($request->jt50_ar)) {
            if($request->jt50_ar==1) {
                $data = $data->where('grab_program.collect_amount', '>=', 50000000);
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

        $count_total  = $data->count();

        $search       = $request->input('search.value');

        $count_filter = $count_total;
        if($search != ''){
            $data     = $data->where(function ($q) use ($search){
                $q->where('grab_program.name', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')
                ->orWhere('headline', 'like', '%'.$search.'%')
                ->orWhere('status', 'like', '%'.$search.'%')
                ->orWhere('target_status', 'like', '%'.$search.'%')
                ->orWhere('target_type', 'like', '%'.$search.'%')
                ->orWhere('target_at', 'like', '%'.$search.'%')
                //->orWhere('grab_organization.name', 'like', '%'.$search.'%')
                ->orWhere('target_amount', 'like', '%'.$search.'%');
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
        ->addColumn('name', function($row){
            return '<a href="'.$row->permalink.'" target="_blank">'.$row->name.'</a>';
        })
        ->addColumn('images', function($row){
            return '<img src="'.$row->image_url.'" style="width:280px; height:auto;">';
        })
        ->addColumn('nominal', function($row){
            $target   = ($row->target_type=='unlimited' && $row->target_amount==0) ? 'Unlimited' : number_format($row->target_amount);
            $collect  = number_format($row->collect_amount);
            $sc       = 'style="cursor:pointer"';
            $btn_edit = '<a href="'.route("adm.leads.org.edit", $row->user_id).'" target="_blank" class="badge badge-sm badge-warning"><i class="fa fa-edit"></i></a>';
          
            $lembaga  = DB::table('grab_organization')->where('user_id', $row->user_id)->first();
            if(isset($lembaga->name)) {
                $org      = '<a href="#" onclick="detailOrg('.$row->user_id.')">'.$lembaga->name.'</a>';
                $wa_param = "'".$row->user_id."','".str_replace("'", "", $lembaga->name)."'";
                $wa       = 'onclick="firstChat('.$wa_param.')"';
              
                $i_mail   = '<i class="fa fa-envelope"></i>';
                $i_telp   = '<i class="fa fa-phone"></i>';
                $i_fb     = 'FB'; //'<i class="fa fa-facebook-f"></i>';
                $i_tw     = 'TW'; //'<i class="fa fa-twitter"></i>';
                $i_ig     = 'IG'; //'<i class="fa fa-instagram"></i>';
                $i_yt     = 'YT'; //'<i class="fa fa-youtube"></i>';

                $mail     = (is_null($lembaga->email) || $lembaga->email=='') ? '<span class="badge badge-sm badge-secondary">'.$i_mail.'</span>' : '<span class="badge badge-sm badge-success">'.$lembaga->email.'</span>';
                $fb       = (is_null($lembaga->facebook) || $lembaga->facebook=='') ? '<span class="badge badge-sm badge-secondary">'.$i_fb.'</span>' : '<a href="'.$lembaga->facebook.'" target="_blank" class="badge badge-sm badge-success">'.$i_fb.'</a>';
                $tw       = (is_null($lembaga->twitter) || $lembaga->twitter=='') ? '<span class="badge badge-sm badge-secondary">'.$i_tw.'</span>' : '<a href="'.$lembaga->twitter.'" target="_blank" class="badge badge-sm badge-success">'.$i_tw.'</a>';
                $ig       = (is_null($lembaga->instagram) || $lembaga->instagram=='') ? '<span class="badge badge-sm badge-secondary">'.$i_ig.'</span>' : '<a href="'.$lembaga->instagram.'" target="_blank" class="badge badge-sm badge-success">'.$i_ig.'</a>';
                $yt       = (is_null($lembaga->youtube) || $lembaga->youtube=='') ? '<span class="badge badge-sm badge-secondary">'.$i_yt.'</span>' : '<a href="'.$lembaga->youtube.'" target="_blank" class="badge badge-sm badge-success">'.$i_yt.'</a>';
                $telp     = (is_null($lembaga->phone) || $lembaga->phone=='') ? '<span class="badge badge-sm badge-secondary">'.$i_telp.'</span>' : '<span class="badge badge-sm badge-success" '.$wa.' '.$sc.'>'.$lembaga->phone.'</span>';
                $last_wa  = '<span class="badge badge-sm badge-warning">-</span>';
                $platform = '<span class="badge badge-sm badge-light">'.$lembaga->platform.'</span>';
                
                return $org.'<br>'.$telp.' '.$last_wa.'<br>'.$mail.' '.$fb.' '.$tw.' '.$ig.' '.$yt.' '.$btn_edit.'<br>'.$target.'<br>'.$collect.'<br>'.$row->target_status.'<br>'.$platform;
            } else {
                return 'Lembaga Tidak Ditemukan '.$btn_edit.'<br>'.$target.'<br>'.$collect.'<br>'.$row->target_status;
            }
        })
        ->addColumn('date', function($row){
            $sc         = 'style="cursor:pointer"';
            $start_date = '<i class="fa fa-play-circle"></i> '.$row->program_created_at;
            $end_date   = (is_null($row->target_at) && is_null($row->target_status)) ? 'Unlimited' : date('Y-m-d', strtotime($row->target_at));
            $end_date   = '<i class="fa fa-stop-circle"></i> '.$end_date;
            $created    = '<i class="fa fa-pencil-alt"></i> '.date('Y-m-d', strtotime($row->created_at));

            $interest   = ($row->is_interest==1) ? '<span id="btninterest_'.$row->id.'"><span class="badge badge-sm badge-success" '.$sc.' onclick="setInterest('.$row->id.', 0)">Menarik</span></span>' : '<span id="btninterest_'.$row->id.'"><span class="badge badge-sm badge-info" '.$sc.' onclick="setInterest('.$row->id.',1)">Menarik?</span></span>';
            $taken      = ($row->is_taken==1) ? '<span id="btntaken_'.$row->id.'"><span class="badge badge-sm badge-success" '.$sc.' onclick="setTaken('.$row->id.', 0)">Tergarap</span></span>' : '<span id="btntaken_'.$row->id.'"><span class="badge badge-sm badge-info" '.$sc.' onclick="setTaken('.$row->id.', 1)">Garap?</span></span>';
            return $start_date.'<br>'.$end_date.'<br>'.$interest.'<br>'.$taken.'<br>'.$created ;
        })
        ->addColumn('headline', function($row){
            return $row->headline;
        })
        ->rawColumns(['name', 'images', 'nominal', 'date', 'headline'])
        ->make(true);
    }

    /**
     * Datatables Chat
     */
    public function orgDatatables(Request $request)
    {
        $data         = DB::table('grab_organization')->orderBy('created_at', 'DESC');

        if(isset($request->ada_wa)) {
            if($request->ada_wa==1) {
                $data = $data->whereNotNull('phone');
            }
        }

        if(isset($request->ada_email)) {
            if($request->ada_email==1) {
                $data = $data->whereNotNull('email');
            }
        }

        $order_column = $request->input('order.0.column');
        $order_dir    = ($request->input('order.0.dir')) ? $request->input('order.0.dir') : 'asc';

        $count_total  = $data->count();

        $search       = $request->input('search.value');

        $count_filter = $count_total;
        if($search != ''){
            $data     = $data->where(function ($q) use ($search){
                $q->where('name', 'like', '%'.$search.'%')
                ->orWhere('address', 'like', '%'.$search.'%')
                ->orWhere('twitter', 'like', '%'.$search.'%')
                ->orWhere('instagram', 'like', '%'.$search.'%')
                ->orWhere('facebook', 'like', '%'.$search.'%')
                ->orWhere('youtube', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
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
        ->addColumn('name', function($row){
            return '<a href="#" target="_blank">'.$row->name.'</a>';
        })
        ->addColumn('contact', function($row){
            $sc       = 'style="cursor:pointer"';
            $i_telp   = '<i class="fa fa-phone"></i>';
            $i_mail   = '<i class="fa fa-envelope"></i>';
            $wa_param = "'".$row->user_id."','".str_replace("'", "", $row->name)."'";
            $wa       = 'onclick="firstChat('.$wa_param.')"';

            $telp     = (is_null($row->phone) || $row->phone=='') ? '<span class="badge badge-sm badge-secondary">'.$i_telp.'</span>' : '<span class="badge badge-sm badge-success" '.$wa.' '.$sc.'>'.$row->phone.'</span>';
            $last_wa  = '<span class="badge badge-sm badge-warning">-</span>';
            $mail     = (is_null($row->email) || $row->email=='') ? '<span class="badge badge-sm badge-secondary">'.$i_mail.'</span>' : '<span class="badge badge-sm badge-success">'.$row->email.'</span>';

            return $telp.' '.$last_wa.'<br>'.$mail;
        })
        ->addColumn('socmed', function($row){
            $sc       = 'style="cursor:pointer"';
            $i_fb     = 'FB';
            $i_tw     = 'TW';
            $i_ig     = 'IG';
            $i_yt     = 'YT';

            $fb       = (is_null($row->facebook) || $row->facebook=='') ? '<span class="badge badge-sm badge-secondary">'.$i_fb.'</span>' : '<a href="'.$row->facebook.'" target="_blank" class="badge badge-sm badge-success">'.$i_fb.'</a>';
            $tw       = (is_null($row->twitter) || $row->twitter=='') ? '<span class="badge badge-sm badge-secondary">'.$i_tw.'</span>' : '<a href="'.$row->twitter.'" target="_blank" class="badge badge-sm badge-success">'.$i_tw.'</a>';
            $ig       = (is_null($row->instagram) || $row->instagram=='') ? '<span class="badge badge-sm badge-secondary">'.$i_ig.'</span>' : '<a href="'.$row->instagram.'" target="_blank" class="badge badge-sm badge-success">'.$i_ig.'</a>';
            $yt       = (is_null($row->youtube) || $row->youtube=='') ? '<span class="badge badge-sm badge-secondary">'.$i_yt.'</span>' : '<a href="'.$row->youtube.'" target="_blank" class="badge badge-sm badge-success">'.$i_yt.'</a>';

            return $fb.' '.$tw.' '.$ig.' '.$yt;
        })
        ->addColumn('action', function($row){
            $btn_edit = '<a href="'.route("adm.leads.org.edit", $row->user_id).'" target="_blank" class="badge badge-sm badge-warning"><i class="fa fa-edit"></i></a>';

            return $btn_edit;
        })
        ->rawColumns(['name', 'contact', 'socmed', 'action'])
        ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editOrganization($id)
    {
        $org  = DB::table('grab_organization')->where('user_id', $id)->first();

        return view('admin.leads.edit_org', compact('org'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOrganization(Request $request, $id)
    {
        try {
            $data = DB::table('grab_organization')->where('user_id', $id)->update([
                'name'        => $request->name,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'email'       => $request->email,
                'instagram'   => $request->ig,
                'facebook'    => $request->fb,
                'youtube'     => $request->yt,
                'twitter'     => $request->tw,
                'fb_pixel'    => $request->pixel,
                'gtm'         => $request->gtm,
                'description' => $request->desc,
                'updated_at'  => $request->date('Y-m-d H:i:s')
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
        $type   = $request->type;

        $org    = DB::table('grab_organization')->select('name', 'phone', 'id')->where('user_id', $id_org)->first();

        if(isset($org->phone)) {
            // type of chat
            if($type == 'fc') {
                // FC here
                $chat    = "Assalamu'alaikum wr.wb, admin *".ucwords(trim($org->name))."*.
Saya Alifa dari tim Bantubersama.com (platform galang dana).
Kami lihat program Anda ".ucwords(trim($org->name))." sangat bagus dan menarik sesuai dengan minat donatur kami.
Bersedia kami bantu promosikan dan optimasi donasinya?🙏🏻✨";
                (new WaBlastController)->sentWAGeneral($org->phone, $chat);
            } else {
                // selain FC
            }

            return array(
                'status'=>'success',
                'org'   => $org->name
            );
        } else {
            return array('status'=>'fail');
        }
    }

    /**
     * Ambil data dari platform lain
     */
    public function grabLeadsAmalsholeh(Request $request)
    {
        if(isset($request->id)) {
            $id = $request->id;
        } else {
            $id = 1;
        }

        $count_inp_org     = 0;
        $count_inp_program = 0;

        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://core.sholeh.app/api/v1/programs?s=a&per_page=3000&page='.$id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->data)) {
                $data = $res->data->data;

                for ($i=0; $i<count($data); $i++) { 
                    // ambil data lembaganya
                    if(isset($data[$i]->user->id)) {
                        $org = DB::table('grab_organization')->select('user_id')->where('user_id', $data[$i]->user->id)->first();
                        if(!isset($org->user_id)) {
                            if(isset($data[$i]->user->description)) {
                                $desc = $this->removeEmoji($data[$i]->user->description);
                            } else {
                                $desc = null;
                            }

                            $user_id = DB::table('grab_organization')->insertGetId([
                                'user_id'     => $data[$i]->user->id,
                                'name'        => $data[$i]->user->name,
                                'avatar'      => $data[$i]->user->avatar,
                                'domicile'    => (isset($data[$i]->user->domicile->text)) ? $data[$i]->user->domicile->text : null,
                                'address'     => $data[$i]->user->address,
                                'fb_pixel'    => (isset($data[$i]->user->fb_pixel)) ? $data[$i]->user->fb_pixel : null,
                                'gtm'         => (isset($data[$i]->user->gtm)) ? $data[$i]->user->gtm : null,
                                'twitter'     => (isset($data[$i]->user->twitter)) ? $data[$i]->user->twitter : null,
                                'instagram'   => (isset($data[$i]->user->instagram)) ? $data[$i]->user->instagram : null,
                                'facebook'    => (isset($data[$i]->user->facebook)) ? $data[$i]->user->facebook : null,
                                'youtube'     => (isset($data[$i]->user->youtube)) ? $data[$i]->user->youtube : null,
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
                    if(!isset($program->id_grab)) {
                        DB::table('grab_program')->insertGetId([
                            'id_grab'            => $data[$i]->id,
                            'category_slug'      => $data[$i]->category_slug,
                            'type'               => $data[$i]->type,
                            'name'               => (isset($data[$i]->name)) ? $this->removeEmoji($data[$i]->name) : null,
                            'slug'               => $data[$i]->slug,
                            'permalink'          => $data[$i]->permalink,
                            'headline'           => (isset($data[$i]->headline)) ? $this->removeEmoji($data[$i]->headline) : null,
                            'content'            => (isset($data[$i]->content)) ? $this->removeEmoji($data[$i]->content) : null,
                            'status'             => $data[$i]->status,
                            'target_status'      => $data[$i]->target_status,
                            'target_type'        => $data[$i]->target_type,
                            'target_at'          => $data[$i]->target_at,
                            'target_amount'      => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->target_amount),
                            'collect_amount'     => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->collect_amount),
                            'remaining_amount'   => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->remaining_amount),
                            'over_at'            => ($data[$i]->over_at!='null') ? date('Y-m-d H:i:s', strtotime($data[$i]->over_at)): null,
                            'is_featured'        => $data[$i]->is_featured,
                            'is_populer_search'  => $data[$i]->is_populer_search,
                            'status_percent'     => $data[$i]->status_percent,
                            'status_date'        => ($data[$i]->status_date=='false') ? null : $data[$i]->status_date,
                            'image_url'          => $data[$i]->image_url,
                            'image_url_thumb'    => $data[$i]->image_url_thumb,
                            'user_id'            => $user_id,
                            'total_donatur'      => str_replace('.', '', $data[$i]->total_donatur),
                            'fb_pixel'           => $data[$i]->fb_pixel,
                            'gtm'                => $data[$i]->gtm,
                            'toggle_dana'        => $data[$i]->toggle_dana,
                            'program_created_at' => (date('Y-m-d', strtotime($data[$i]->program_created_at))) ? date('Y-m-d', strtotime($data[$i]->program_created_at)) : date('Y-m-d'),
                            'tags_name'          => (isset($data[$i]->tags->title)) ? $data[$i]->tags->title : null,
                            'is_favorite'        => ($data[$i]->is_favorite=='false') ? 0 : 1,
                            'fund_display'       => $data[$i]->fund_display
                        ]);
                        $count_inp_program++;
                    }
                }
            } else {
                echo "<br>no data";
            }

            echo "FINISHED, <br>Total Ambil Data : ".count($data);
            echo '<br>Total Program Bagu : '.$count_inp_program;
            echo '<br>Total Lembaga Bagu : '.$count_inp_org;
        }
    }

    /**
     * Ambil data dari platform lain
     */
    public function grabLeadsSharingHappiness(Request $request)
    {
        if(isset($request->id)) {
            $id = $request->id;
        } else {
            $id = 1;
        }

        $count_inp_org     = 0;
        $count_inp_program = 0;

        $curl             = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://be.sharinghappiness.org/api/v1/program?keyword=a&perPage=2000&page='.$id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response         = curl_exec($curl);
        $err              = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo 'Pesan gagal terkirim, error :' . $err;
        } else {
            $res = json_decode($response);

            if(isset($res->result)) {
                $data = $res->result->data;

                for ($i=0; $i<count($data); $i++) { 
                    // ambil data lembaganya
                    if(isset($data[$i]->user->name)) {
                        $org = DB::table('grab_organization')->select('user_id')->where('name', $data[$i]->user->name)->first();
                        if(!isset($org->user_id)) {
                            $user_id = DB::table('grab_organization')->insertGetId([
                                'user_id'     => date('ymdhis'),
                                'name'        => $data[$i]->user->name,
                                'avatar'      => $data[$i]->user->picture,
                                'platform'    => 'sharinghappiness'
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
                    if(!isset($program->id_grab) && isset($data[$i]->galleries[0]->image) && !is_null($data[$i]->galleries[0]->image) && !is_null($data[$i]->cover_picture->image)) {
                        DB::table('grab_program')->insertGetId([
                            'id_grab'            => $data[$i]->id,
                            'category_slug'      => (isset($data[$i]->category->slug)) ? $data[$i]->category->slug : null,
                            'name'               => (isset($data[$i]->title)) ? $this->removeEmoji($data[$i]->title) : null,
                            'slug'               => $data[$i]->slug,
                            'permalink'          => 'https://sharinghappiness.org/'.$data[$i]->slug,
                            'headline'           => (isset($data[$i]->city->name)) ? $this->removeEmoji($data[$i]->city->name) : null,
                            'content'            => null,
                            'status'             => $data[$i]->status,
                            'target_status'      => null,
                            'target_type'        => null,
                            'target_at'          => $data[$i]->end_date,
                            'target_amount'      => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->target),
                            'collect_amount'     => str_replace([' ', '.', 'Rp', ',', '-'], '', $data[$i]->collected),
                            'remaining_amount'   => 0,
                            'over_at'            => ($data[$i]->end_date!='null' && date('Y', strtotime($data[$i]->end_date))>1970) ? $data[$i]->end_date: null,
                            'is_featured'        => $data[$i]->is_optimized,
                            'is_populer_search'  => $data[$i]->is_recommended,
                            'status_percent'     => $data[$i]->discount_price,
                            'status_date'        => null,
                            'image_url'          => (isset($data[$i]->galleries[0]->image)) ? $data[$i]->galleries[0]->image : $data[$i]->cover_picture->image,
                            'image_url_thumb'    => $data[$i]->cover_picture->image,
                            'user_id'            => $user_id,
                            'total_donatur'      => str_replace('.', '', $data[$i]->transaction_count),
                            'fb_pixel'           => null,
                            'gtm'                => null,
                            'toggle_dana'        => null,
                            'program_created_at' => (date('Y-m-d', strtotime($data[$i]->created_at))) ? date('Y-m-d', strtotime($data[$i]->created_at)) : date('Y-m-d'),
                            'tags_name'          => null,
                            'is_favorite'        => 0,
                            'fund_display'       => null
                        ]);
                        $count_inp_program++;
                    }
                }
            } else {
                echo "<br>no data";
            }

            echo "FINISHED, ";
            echo "<br>Total Ambil Data : ".count($data);
            echo '<br>Total Program Baru : '.$count_inp_program;
            echo '<br>Total Lembaga Baru : '.$count_inp_org;
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

}
