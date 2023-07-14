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
        $sum_donate      = Transaction::where('status', 'success')->count('id');
        $sum_paid        = Transaction::where('status', 'success')->sum('nominal_final');
        $sum_transaction = Transaction::count('id');
        $sum_page_viewed = Program::sum('count_view');

        $dn              = date('Y-m-d');
        $donate_success  = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count()
        );
        $donate_success_rp = array(
            0 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', 'success')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final')
        );
        $donate_draft    = array(
            0 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', $dn.'%')->count(),
            1 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->count(),
            2 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->count(),
            3 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->count(),
            4 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->count()
        );
        $donate_draft_rp = array(
            0 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', $dn.'%')->sum('nominal_final'),
            1 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-1 day')).'%')->sum('nominal_final'),
            2 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-2 day')).'%')->sum('nominal_final'),
            3 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-3 day')).'%')->sum('nominal_final'),
            4 => Transaction::select('id')->where('status', 'draft')->where('created_at', 'like', date('Y-m-d', strtotime($dn.'-4 day')).'%')->sum('nominal_final')
        );
        return view('admin.dashboard', compact('sum_donate', 'sum_paid', 'sum_transaction', 'sum_page_viewed', 'donate_success', 'donate_success_rp', 'donate_draft', 'donate_draft_rp'));
    }
}
