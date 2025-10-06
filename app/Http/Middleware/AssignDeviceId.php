<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssignDeviceId
{
    public function handle(Request $request, Closure $next)
    {
        $did = $request->cookie('bb_did');

        if (!$did) {
            $did = (string) Str::uuid();
            // queue cookie agar otomatis dikirim bersama response
            cookie()->queue(cookie(
                'bb_did',
                $did,
                60 * 24 * 365,    // menit (1 tahun)
                '/',              // path
                null,             // domain (null => current host)
                true,             // secure (HTTPS only)
                true,             // httpOnly
                false,            // raw
                'Lax'             // sameSite - Lax umumnya OK for FB in-app; Strict bisa masalah
            ));
        }

        // attach for easy access in controller
        $request->attributes->set('bb_did', $did);

        return $next($request);
    }
}
