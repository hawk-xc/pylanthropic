<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\PaymentController;
use Carbon\Carbon;

use App\Models\Program;
use App\Models\Transaction;
use App\Models\PaymentType;
use App\Models\Donatur;
use App\Models\TrackingVisitor;
use App\Models\SpamLog;

use Illuminate\Support\Facades\Http;

class AntiSpamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function checkSpam($id_trans)
    {
        // 

        // --- Default (aman)
        return [
            'is_suspect'     => 0,
            'invoice_number' => '',
        ];
    }

}
