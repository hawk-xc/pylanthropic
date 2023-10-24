<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models;
use App\Models\Program;
use App\Models\Transaction;
use App\Models\ProgramInfo;
use App\Models\TrackingVisitor;

use App\Http\Controllers\FormatDateController;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $slug    = $request->slug;
        $program = Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status', 'organization.logo')
                    ->join('organization', 'program.organization_id', 'organization.id')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->name)) {
            // update count view
            Program::where('id', $program->id)->update([
                'count_view' => $program->count_view+1,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $transaction  = Transaction::where('program_id', $program->id)->where('status', 'success');
            $sum_amount   = $transaction->sum('nominal_final');
            $count_donate = $transaction->count();
            $count_payout = \App\Models\Payout::select('id')->where('program_id', $program->id)->where('status', 'paid')->count();
            $sum_news     = ProgramInfo::select('id')->where('program_id', $program->id)->where('is_publish', 1)->count();
            $info         = ProgramInfo::where('program_id', $program->id)->where('is_publish', 1)
                            ->orderBy('created_at', 'DESC')->first();
            $donate       = $transaction->join('donatur', 'donatur.id', 'transaction.donatur_id')
                            ->select('transaction.nominal_final', 'transaction.created_at', 'transaction.is_show_name', 'transaction.message', 'donatur.name')
                            ->orderBy('transaction.created_at', 'DESC')->limit(5)->get();
            $donate->map(function($donate, $key) {
                        return $donate->date_string = (new FormatDateController)->timeDonate($donate->created_at);
                    });

            // insert tracking visitor
            TrackingVisitor::create([
                'program_id'      => $program->id,
                'visitor_code'    => 1,
                'page_view'       => 'landing_page',
                'nominal'         => 0,
                'payment_type_id' => null,
                'ref_code'        => (isset($request->ref)) ? strip_tags($request->ref) : null,
                'utm_source'      => (isset($request->a)) ? strip_tags($request->a) : null,
                'utm_medium'      => (isset($request->as)) ? strip_tags($request->as) : null,
                'utm_campaign'    => null,
                'utm_content'     => (isset($request->k)) ? strip_tags($request->k) : null
            ]);

            return view('public.program', 
                    compact('program', 'sum_amount', 'count_donate', 'sum_news', 'count_payout', 'info', 'donate'));
        } else {
            return view('public.not_found');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $program = Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status')
                    ->join('organization', 'program.organization_id', 'organization.id')
                    ->whereNotNull('program.approved_at')
                    ->where('end_date', '>', date('Y-m-d'));

        if($request->has('kategori')) {
            if($request->kategori!='semua') {
                $program->join('program_categories', 'program.id', 'program_categories.program_id');
                $program->join('program_category', 'program_category.id', 'program_categories.program_category_id');
                $program->where('program_category.slug', $request->kategori);
            }
            // else = semua kategori jadi tidak perlu di filter
        }

        if($request->has('key')) {
            $program->where('program.title', 'like', '%'.trim($request->key).'%');
        }

        if($request->has('sort')) {
            if($request->sort=='terbaru') {
                $program = $program->orderBy('program.approved_at', 'DESC');
            } elseif($request->sort=='segera_berakhir') {
                $program = $program->orderBy('program.end_date', 'ASC');
            } elseif($request->sort=='terbanyak') {
                $program = $program->orderBy('program.end_date', 'DESC');
            } elseif($request->sort=='sedikit') {
                $program = $program->orderBy('program.end_date', 'DESC');
            }
        } else {
            // Secara default = TERBARU
            $program = $program->orderBy('program.approved_at', 'DESC');
        }

        $program = $program->limit(8)->get();
        $program->map(function($program, $key) {
                        $sum_amount = Models\Transaction::where('program_id', $program->id)->where('status', 'success')
                                    ->sum('nominal_final');
                        return $program->sum_amount = $sum_amount;
                    });
        return view('public.program_list', compact('program'));
    }

    /**
     * Display a listing of the resource.
     */
    public function info(Request $request)
    {
        $program = Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status', 'organization.logo')
                    ->join('organization', 'program.organization_id', 'organization.id')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->name)) {
            $info = ProgramInfo::select('program_info.*', 'program.slug')->join('program', 'program.id', 'program_info.program_id')
                ->where('program_info.is_publish', 1)->where('slug', $request->slug)
                ->orderBy('program_info.created_at', 'DESC')->limit(10)->get();
            return view('public.program_info', compact('info', 'program'));
        } else {
            return view('public.not_found');
        }
    }

    /**
     * Count click Baca Selengkapnya.
     */
    public function countReadMore(Request $request)
    {
        $program = Program::where('is_publish', 1)->select('slug', 'id', 'count_read_more')
                    ->where('slug', $request->slug)->whereNotNull('program.approved_at')->first();
        if(isset($program->slug)) {
            // update count_read_more
            Program::where('id', $program->id)->update([
                'count_read_more' => $program->count_read_more+1,
                'updated_at'      => date('Y-m-d H:i:s')
            ]);
            return 'success';
        } else {
            return 'failed';
        }
    }
}
