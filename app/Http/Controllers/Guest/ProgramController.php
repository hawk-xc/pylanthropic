<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.program', compact('slug'));
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.program_list', compact('slug'));
    }

}
