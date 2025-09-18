<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models;
use App\Models\ShortLinkModel;
use App\Models\DonaturShortLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Cache::remember('banner_public', 60 * 60, function () {
            $today = Carbon::today();

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

            return compact('slider', 'popup');
        });

        $programs = Cache::remember('program_public', 60 * 60, function () {
            $category = Models\ProgramCategory::where('is_show', 1)->orderBy('sort_number', 'ASC')->get();
            $selected = Models\Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status')->join('organization', 'program.organization_id', 'organization.id')->where('is_recommended', 1)->whereNotNull('program.approved_at')->where('end_date', '>', date('Y-m-d'))->orderBy('program.created_at', 'DESC')->limit(6)->get();
            $selected->map(function ($selected, $key) {
                $sum_amount = Models\Transaction::where('program_id', $selected->id)->where('status', 'success')->sum('nominal_final');
                if ($selected->show_minus > 0 && !is_null($selected->show_minus) && $sum_amount > 0) {
                    return $selected->sum_amount = $sum_amount - ($sum_amount * $selected->show_minus) / 100;
                } else {
                    return $selected->sum_amount = $sum_amount;
                }
            });

            $urgent = Models\Program::select('program.*', 'organization.name', 'organization.status')->join('organization', 'program.organization_id', 'organization.id')->where('is_publish', 1)->where('is_urgent', 1)->whereNotNull('program.approved_at')->where('end_date', '>', date('Y-m-d'))->orderBy('program.created_at', 'DESC')->limit(6)->get();
            $urgent->map(function ($urgent, $key) {
                $sum_amount = Models\Transaction::where('program_id', $urgent->id)->where('status', 'success')->sum('nominal_final');
                if ($urgent->show_minus > 0 && !is_null($urgent->show_minus) && $sum_amount > 0) {
                    return $urgent->sum_amount = $sum_amount - ($sum_amount * $urgent->show_minus) / 100;
                } else {
                    return $urgent->sum_amount = $sum_amount;
                }
            });

            $newest = Models\Program::where('is_publish', 1)->select('program.*', 'organization.name', 'organization.status')->join('organization', 'program.organization_id', 'organization.id')->where('is_show_home', 1)->where('is_recommended', 0)->whereNotNull('program.approved_at')->where('end_date', '>', date('Y-m-d'))->orderBy('program.created_at', 'DESC')->limit(7)->get();
            $newest->map(function ($newest, $key) {
                $sum_amount = Models\Transaction::where('program_id', $newest->id)->where('status', 'success')->sum('nominal_final');
                if ($newest->show_minus > 0 && !is_null($newest->show_minus) && $sum_amount > 0) {
                    return $newest->sum_amount = $sum_amount - ($sum_amount * $newest->show_minus) / 100;
                } else {
                    return $newest->sum_amount = $sum_amount;
                }
            });
            return compact('category', 'selected', 'newest', 'urgent');
        });

        $data = array_merge($banners, $programs);

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

        $shortLink->click_counter = $shortLink->increment+1;
        $shortLink->last_accessed_at = now();

        $shortLink->save();

        return redirect()->away($shortLink->direct_link);
    }
}
