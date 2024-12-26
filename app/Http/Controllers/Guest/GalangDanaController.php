<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Program;

class GalangDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('public.galangdana.index');
    }
    /**
     * Display a listing of the resource.
     */
    public function form(Request $request)
    {
        return view('public.galangdana.form');
    }

    /**
     * Display a listing of the resource.
     */
    public function submit(Request $request)
    {
        return view('public.galangdana.thanks');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function thanks(Request $request)
    {
        return view('public.galangdana.index');
    }

}
