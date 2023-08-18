<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Program;

class DashboardController extends Controller
{
    /**
     * Show the application's Dashboard Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dn              = date('Y-m-d');
        $sum_donate      = Transaction::where('status', 'success')->count('id');
        $sum_paid        = Transaction::where('status', 'success')->sum('nominal_final');
        $sum_paid_now    = Transaction::where('status', 'success')
                            ->where('created_at', 'like', date('Y-m').'%')->sum('nominal_final');
        $sum_transaction = Transaction::count('id');
        $sum_page_viewed = Program::sum('count_view');

        $donate_success  = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count()
        );
        $donate_success_rp = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final')
        );
        $donate_draft    = array(
            0 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count(),
            5 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->count(),
            6 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->count()
        );
        $donate_draft_rp = array(
            0 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-5 day')).'%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('status', '!=', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-6 day')).'%')->sum('nominal_final')
        );

        // Donasi Perjam
        $donate_perjam_count  = array(
            0 => Transaction::select('id')->where('created_at', 'like', '% 00:%')->count(),
            1 => Transaction::select('id')->where('created_at', 'like', '% 01:%')->count(),
            2 => Transaction::select('id')->where('created_at', 'like', '% 02:%')->count(),
            3 => Transaction::select('id')->where('created_at', 'like', '% 03:%')->count(),
            4 => Transaction::select('id')->where('created_at', 'like', '% 04:%')->count(),
            5 => Transaction::select('id')->where('created_at', 'like', '% 05:%')->count(),
            6 => Transaction::select('id')->where('created_at', 'like', '% 06:%')->count(),
            7 => Transaction::select('id')->where('created_at', 'like', '% 07:%')->count(),
            8 => Transaction::select('id')->where('created_at', 'like', '% 08:%')->count(),
            9 => Transaction::select('id')->where('created_at', 'like', '% 09:%')->count(),
            10 => Transaction::select('id')->where('created_at', 'like', '% 10:%')->count(),
            11 => Transaction::select('id')->where('created_at', 'like', '% 11:%')->count(),
            12 => Transaction::select('id')->where('created_at', 'like', '% 12:%')->count(),
            13 => Transaction::select('id')->where('created_at', 'like', '% 13:%')->count(),
            14 => Transaction::select('id')->where('created_at', 'like', '% 14:%')->count(),
            15 => Transaction::select('id')->where('created_at', 'like', '% 15:%')->count(),
            16 => Transaction::select('id')->where('created_at', 'like', '% 16:%')->count(),
            17 => Transaction::select('id')->where('created_at', 'like', '% 17:%')->count(),
            18 => Transaction::select('id')->where('created_at', 'like', '% 18:%')->count(),
            19 => Transaction::select('id')->where('created_at', 'like', '% 19:%')->count(),
            20 => Transaction::select('id')->where('created_at', 'like', '% 20:%')->count(),
            21 => Transaction::select('id')->where('created_at', 'like', '% 21:%')->count(),
            22 => Transaction::select('id')->where('created_at', 'like', '% 22:%')->count(),
            23 => Transaction::select('id')->where('created_at', 'like', '% 23:%')->count()
        );
        $donate_perjam_sum  = array(
            0 => Transaction::select('id')->where('created_at', 'like', '% 00:%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('created_at', 'like', '% 01:%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('created_at', 'like', '% 02:%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('created_at', 'like', '% 03:%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('created_at', 'like', '% 04:%')->sum('nominal_final'),
            5 => Transaction::select('id')->where('created_at', 'like', '% 05:%')->sum('nominal_final'),
            6 => Transaction::select('id')->where('created_at', 'like', '% 06:%')->sum('nominal_final'),
            7 => Transaction::select('id')->where('created_at', 'like', '% 07:%')->sum('nominal_final'),
            8 => Transaction::select('id')->where('created_at', 'like', '% 08:%')->sum('nominal_final'),
            9 => Transaction::select('id')->where('created_at', 'like', '% 09:%')->sum('nominal_final'),
            10 => Transaction::select('id')->where('created_at', 'like', '% 10:%')->sum('nominal_final'),
            11 => Transaction::select('id')->where('created_at', 'like', '% 11:%')->sum('nominal_final'),
            12 => Transaction::select('id')->where('created_at', 'like', '% 12:%')->sum('nominal_final'),
            13 => Transaction::select('id')->where('created_at', 'like', '% 13:%')->sum('nominal_final'),
            14 => Transaction::select('id')->where('created_at', 'like', '% 14:%')->sum('nominal_final'),
            15 => Transaction::select('id')->where('created_at', 'like', '% 15:%')->sum('nominal_final'),
            16 => Transaction::select('id')->where('created_at', 'like', '% 16:%')->sum('nominal_final'),
            17 => Transaction::select('id')->where('created_at', 'like', '% 17:%')->sum('nominal_final'),
            18 => Transaction::select('id')->where('created_at', 'like', '% 18:%')->sum('nominal_final'),
            19 => Transaction::select('id')->where('created_at', 'like', '% 19:%')->sum('nominal_final'),
            20 => Transaction::select('id')->where('created_at', 'like', '% 20:%')->sum('nominal_final'),
            21 => Transaction::select('id')->where('created_at', 'like', '% 21:%')->sum('nominal_final'),
            22 => Transaction::select('id')->where('created_at', 'like', '% 22:%')->sum('nominal_final'),
            23 => Transaction::select('id')->where('created_at', 'like', '% 23:%')->sum('nominal_final')
        );

        return view('admin.dashboard', compact('sum_donate', 'sum_paid', 'sum_paid_now', 'sum_transaction', 'sum_page_viewed', 'donate_success', 'donate_success_rp', 'donate_draft', 'donate_draft_rp', 'donate_perjam_count', 'donate_perjam_sum'));
    }
}
