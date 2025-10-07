<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterIpAccess
{
    /**
     * Prefix IP yang diblokir
     */
    protected array $blockedPrefixes = [
        '172.64.0.0/13',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        if ($this->isBlocked($ip)) {
            abort(403);
        }

        return $next($request);
    }

    private function isBlocked(string $ip): bool
    {
        foreach ($this->blockedPrefixes as $cidr) {
            if ($this->ipInRange($ip, $cidr)) {
                return true;
            }
        }
        return false;
    }

    private function ipInRange(string $ip, string $cidr): bool
    {
        list($subnet, $mask) = explode('/', $cidr);
        $ipDecimal = ip2long($ip);
        $subnetDecimal = ip2long($subnet);
        $maskDecimal = -1 << (32 - (int)$mask);

        return ($ipDecimal & $maskDecimal) === ($subnetDecimal & $maskDecimal);
    }
}
