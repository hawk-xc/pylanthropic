<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Transaction;

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
            $transaction  = Transaction::where('program_id', $program->id)->where('status', 'success');
            $sum_amount   = $transaction->sum('nominal_final');
            $count_donate = $transaction->count();
            $count_payout = \App\Models\Payout::select('id')->where('program_id', $program->id)->where('status', 'paid')->count();
            $sum_news     = \App\Models\ProgramInfo::select('id')->where('program_id', $program->id)->where('is_publish', 1)->count();
            $info         = \App\Models\ProgramInfo::where('program_id', $program->id)->where('is_publish', 1)
                            ->orderBy('created_at', 'DESC')->first();
            $donate       = $transaction->join('donatur', 'donatur.id', 'transaction.donatur_id')
                            ->select('transaction.nominal', 'transaction.paid_at', 'transaction.is_show_name', 'transaction.message', 'donatur.name')
                            ->limit(5)->get();
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
                    ->where('end_date', '>', date('Y-m-d'))->orderBy('program.created_at', 'DESC')->limit(8)->get();
        return view('public.program_list', compact('program'));
    }

}
