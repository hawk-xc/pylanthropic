<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\ShortLinkModel;
use App\Models\DonaturShortLink;
use App\Models\ProgramCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today();

        /**
         * =============================
         * BANNERS (CACHED 1 JAM)
         * =============================
         */
        $banners = Cache::remember('banner_public', 60 * 60, function () use ($today) {

            $slider = Models\Banner::where('is_publish', 1)
                ->where('type', 'banner')
                ->where(function ($q) use ($today) {
                    $q->where('is_forever', 1)
                        ->orWhere(function ($q2) use ($today) {
                            $q2->whereNotNull('expire_date')
                                ->where('expire_date', '>=', $today);
                        });
                })
                ->orderBy('title', 'ASC')
                ->get();

            $popup = Models\Banner::where('is_publish', 1)
                ->where('type', 'popup')
                ->where(function ($q) use ($today) {
                    $q->where('is_forever', 1)
                        ->orWhere(function ($q2) use ($today) {
                            $q2->whereNotNull('expire_date')
                                ->where('expire_date', '>=', $today);
                        });
                })
                ->first();

            return compact('slider', 'popup'); // ⬅️ sudah array
        });


        /**
         * PROGRAM SECTION
         */
        $category = ProgramCategory::all(); // ⬅️ tidak perlu () saat merge nanti

        $programs = function () use ($category) {

            // SELECTED
            $selected = Models\Program::select('program.*', 'organization.name', 'organization.status')
                ->join('organization', 'program.organization_id', 'organization.id')
                ->where('is_publish', 1)
                ->whereNotNull('program.approved_at')
                ->where('end_date', '>', date('Y-m-d'))
                ->orderBy('program.created_at', 'DESC')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $sum = Models\Transaction::where('program_id', $item->id)
                        ->where('status', 'success')
                        ->sum('nominal_final');

                    $item->sum_amount = ($item->show_minus && $sum > 0)
                        ? $sum - ($sum * $item->show_minus / 100)
                        : $sum;

                    return $item;
                });

            // URGENT
            $urgent = Models\Program::select('program.*', 'organization.name', 'organization.status')
                ->join('organization', 'program.organization_id', 'organization.id')
                ->where('is_publish', 1)
                ->where('is_urgent', 1)
                ->whereNotNull('program.approved_at')
                ->where('end_date', '>', date('Y-m-d'))
                ->orderBy('program.created_at', 'DESC')
                ->limit(6)
                ->get()
                ->map(function ($item) {
                    $sum = Models\Transaction::where('program_id', $item->id)
                        ->where('status', 'success')
                        ->sum('nominal_final');

                    $item->sum_amount = ($item->show_minus && $sum > 0)
                        ? $sum - ($sum * $item->show_minus / 100)
                        : $sum;

                    return $item;
                });


            // NEWEST
            $newest = Models\Program::select('program.*', 'organization.name', 'organization.status')
                ->join('organization', 'program.organization_id', 'organization.id')
                ->where('is_publish', 1)
                ->where('is_show_home', 1)
                ->where('is_recommended', 0)
                ->whereNotNull('program.approved_at')
                ->where('end_date', '>', date('Y-m-d'))
                ->orderBy('program.created_at', 'DESC')
                ->limit(7)
                ->get()
                ->map(function ($item) {
                    $sum = Models\Transaction::where('program_id', $item->id)
                        ->where('status', 'success')
                        ->sum('nominal_final');

                    $item->sum_amount = ($item->show_minus && $sum > 0)
                        ? $sum - ($sum * $item->show_minus / 100)
                        : $sum;

                    return $item;
                });

            return compact('category', 'selected', 'urgent', 'newest');
        };


        /**
         * MERGE DATA KE VIEW
         */
        $data = array_merge($banners, $programs()); // ⬅️ FIX

        return view('public.index', $data);
    }



    public function aboutUs()
    {
        return view('public.public-page.about');
    }

    public function termsAndCondition()
    {
        return view('public.public-page.terms');
    }

    public function questionsCenter()
    {
        return view('public.public-page.faq');
    }

    public function shortLink(string $code)
    {
        $shortLink = ShortLinkModel::where('code', $code)->where('is_active', 1)->first();

        if (!$shortLink) {
            abort(404, 'Short link not found or inactive');
        }

        // for click counter purpose
        // $shortLink->increment('click_count');
        // $shortLink->last_accessed_at = now();

        $shortLink->save();

        return redirect()->away('https://bantubersama.com/' . $shortLink->direct_link);
    }

    public function userShortLink(string $code)
    {
        $shortLink = DonaturShortLink::where('code', $code)->where('is_active', 1)->first();

        if (!$shortLink) {
            abort(404, 'Short link not found or inactive');
        }

        $shortLink->click_counter = $shortLink->increment + 1;
        $shortLink->last_accessed_at = now();

        $shortLink->save();

        return redirect()->away($shortLink->direct_link);
    }
}
