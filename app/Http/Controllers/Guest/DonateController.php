<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function amount()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.donate_amount', compact('slug'));
    }

    /**
     * Display a listing of the resource.
     */
    public function payment()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.payment', compact('slug'));
    }

    /**
     * Display a listing of the resource.
     */
    public function checkout()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.checkout', compact('slug'));
    }

    /**
     * Display a listing of the resource.
     */
    public function paymentInfo()
    {
        $slug = 'slug-ini-judulnya';
        return view('public.payment_info', compact('slug'));
    }

}
