<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Program;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the application's Dashboard Admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dn = Carbon::today();

        $sum_donate = Transaction::where('status', 'success')->count();
        $sum_paid = Transaction::where('status', 'success')->sum('nominal_final');
        $sum_paid_now = Transaction::where('status', 'success')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('nominal_final');
        $sum_transaction = Transaction::count();
        $sum_page_viewed = Program::sum('count_view');

        $startDate = Carbon::today()->subDays(6);
        $transactions = Transaction::select(DB::raw('DATE(created_at) as date'), DB::raw("SUM(CASE WHEN status = 'success' THEN nominal_final ELSE 0 END) as success_amount"), DB::raw("COUNT(CASE WHEN status = 'success' THEN 1 END) as success_count"), DB::raw("SUM(CASE WHEN status != 'success' THEN nominal_final ELSE 0 END) as draft_amount"), DB::raw("COUNT(CASE WHEN status != 'success' THEN 1 END) as draft_count"))->whereDate('created_at', '>=', $startDate)->groupBy(DB::raw('DATE(created_at)'))->orderBy('date', 'desc')->get()->keyBy('date');

        $donate_success = [];
        $donate_success_rp = [];
        $donate_draft = [];
        $donate_draft_rp = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $dn->copy()->subDays($i)->toDateString();
            $dayData = $transactions->get($date);

            $donate_success[$i] = $dayData->success_count ?? 0;
            $donate_success_rp[$i] = $dayData->success_amount ?? 0;
            $donate_draft[$i] = $dayData->draft_count ?? 0;
            $donate_draft_rp[$i] = $dayData->draft_amount ?? 0;
        }

        return view('admin.dashboard', compact('sum_donate', 'sum_paid', 'sum_paid_now', 'sum_transaction', 'sum_page_viewed', 'donate_success', 'donate_success_rp', 'donate_draft', 'donate_draft_rp'));
    }
}
