<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slider   = Models\Slider::where('is_show', 1)->orderBy('sort_number', 'ASC')->get();
        $category = Models\ProgramCategory::where('is_show', 1)->orderBy('sort_number', 'ASC')->get();
        $selected = Models\Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status')
                    ->join('organization', 'program.organization_id', 'organization.id')
                    ->where('is_recommended', 1)->whereNotNull('program.approved_at')
                    ->where('end_date', '>', date('Y-m-d'))->orderBy('program.created_at', 'DESC')->limit(6)->get();
        $selected->map(function($selected, $key) {
                        $sum_amount = Models\Transaction::where('program_id', $selected->id)->where('status', 'success')
                                    ->sum('nominal_final');
                        return $selected->sum_amount = $sum_amount;
                    });
        $newest   = Models\Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status')
                    ->join('organization', 'program.organization_id', 'organization.id')
                    ->where('is_show_home', 1)->where('is_recommended', 0)
                    ->whereNotNull('program.approved_at')->where('end_date', '>', date('Y-m-d'))
                    ->orderBy('program.created_at', 'DESC')->limit(7)->get();
        $newest->map(function($newest, $key) {
                        $sum_amount = Models\Transaction::where('program_id', $newest->id)->where('status', 'success')
                                    ->sum('nominal_final');
                        return $newest->sum_amount = $sum_amount;
                    });
        return view('public.index', compact('category', 'slider', 'selected', 'newest'));
    }

}
