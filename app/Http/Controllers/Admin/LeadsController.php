<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $data         = DB::table('grab_program')->select('grab_program.*', 'grab_organization.name as lembaga', 'twitter', 'instagram', 'facebook', 'youtube', 'email', 'phone')
        ->leftJoin('grab_organization', 'grab_organization.user_id', 'grab_program.user_id')->orderBy('program_created_at', 'DESC');

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
                ->orWhere('grab_organization.name', 'like', '%'.$search.'%')
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
            $target  = ($row->target_type=='unlimited' && $row->target_amount==0) ? 'Unlimited' : number_format($row->target_amount);
            $collect = number_format($row->collect_amount);
            $org     = '<a href="#" onclick="detailOrg('.$row->user_id.')">'.$row->lembaga.'</a>';

            $sc      = 'style="cursor:pointer"';
            $i_mail  = '<i class="fa fa-envelope"></i>';
            $i_telp  = '<i class="fa fa-phone"></i>';
            $i_fb    = 'FB'; //'<i class="fa fa-facebook-f"></i>';
            $i_tw    = 'TW'; //'<i class="fa fa-twitter"></i>';
            $i_ig    = 'IG'; //'<i class="fa fa-instagram"></i>';
            $i_yt    = 'YT'; //'<i class="fa fa-youtube"></i>';

            $mail    = (is_null($row->email) || $row->email=='') ? '<span class="badge badge-sm badge-secondary">'.$i_mail.'</span>' : '<span class="badge badge-sm badge-success">'.$row->email.'</span>';
            $telp    = (is_null($row->phone) || $row->phone=='') ? '<span class="badge badge-sm badge-secondary">'.$i_telp.'</span>' : '<span class="badge badge-sm badge-success">'.$row->phone.'</span>';
            $fb      = (is_null($row->facebook) || $row->facebook=='') ? '<span class="badge badge-sm badge-secondary">'.$i_fb.'</span>' : '<a href="'.$row->facebook.'" target="_blank" class="badge badge-sm badge-success">'.$i_fb.'</a>';
            $tw      = (is_null($row->twitter) || $row->twitter=='') ? '<span class="badge badge-sm badge-secondary">'.$i_tw.'</span>' : '<a href="'.$row->twitter.'" target="_blank" class="badge badge-sm badge-success">'.$i_tw.'</a>';
            $ig      = (is_null($row->instagram) || $row->instagram=='') ? '<span class="badge badge-sm badge-secondary">'.$i_ig.'</span>' : '<a href="'.$row->instagram.'" target="_blank" class="badge badge-sm badge-success">'.$i_ig.'</a>';
            $yt      = (is_null($row->youtube) || $row->youtube=='') ? '<span class="badge badge-sm badge-secondary">'.$i_yt.'</span>' : '<a href="'.$row->youtube.'" target="_blank" class="badge badge-sm badge-success">'.$i_yt.'</a>';

            return $org.'<br>'.$mail.' '.$telp.' '.$fb.' '.$tw.' '.$ig.' '.$yt.'<br>'.$target.'<br>'.$collect.'<br>'.$row->target_status;
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
     * Ambil data dari platform lain
     */
    public function grabLeadsAmalsholeh(Request $request)
    {
        if(isset($request->id)) {
            $id = $request->id;
        } else {
            $id = 1;
        }

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
                                'description' => (isset($data[$i]->user->description)) ? $data[$i]->user->description : null,
                            ]);
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
                            'name'               => $data[$i]->name,
                            'slug'               => $data[$i]->slug,
                            'permalink'          => $data[$i]->permalink,
                            'headline'           => $data[$i]->headline,
                            'content'            => $data[$i]->content,
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
                            'program_created_at' => date('Y-m-d', strtotime($data[$i]->program_created_at)),
                            'tags_name'          => (isset($data[$i]->tags->title)) ? $data[$i]->tags->title : null,
                            'is_favorite'        => ($data[$i]->is_favorite=='false') ? 0 : 1,
                            'fund_display'       => $data[$i]->fund_display
                        ]);
                    }
                }
            } else {
                echo "<br>no data";
            }

            echo "FINISHED, jumlah : ".count($res);
        }
    }

}
