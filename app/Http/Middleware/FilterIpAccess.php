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
        '173.245.48.0/20',
        '172.71.124.0/20',
        // '103.21.244.0/22',
        // '103.22.200.0/22',
        // '103.31.4.0/22',
        // '141.101.64.0/18',
        // '108.162.192.0/18',
        // '190.93.240.0/20',
        // '188.114.96.0/20',
        // '197.234.240.0/22',
        // '198.41.128.0/17',
        // '162.158.0.0/15',
        // '104.16.0.0/13',
        // '104.24.0.0/14',
        // '172.64.0.0/13',
        // '131.0.72.0/22',

        // // IPv6
        // '2400:cb00::/32',
        // '2606:4700::/32',
        // '2803:f800::/32',
        // '2405:b500::/32',
        // '2405:8100::/32',
        // '2a06:98c0::/29',
        // '2c0f:f248::/32',
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
        if (strpos($cidr, '/') === false) {
            return $ip === $cidr;
        }

        list($subnet, $maskBits) = explode('/', $cidr);

        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);

        if ($ipBin === false || $subnetBin === false) {
            return false;
        }

        $len = strlen($ipBin) * 8;
        $maskBits = (int)$maskBits;

        if ($maskBits < 0 || $maskBits > $len) {
            return false;
        }

        $mask = str_repeat("f", $maskBits >> 2);
        switch ($maskBits % 4) {
            case 1: $mask .= "8"; break;
            case 2: $mask .= "c"; break;
            case 3: $mask .= "e"; break;
        }
        $mask = str_pad($mask, $len / 4, "0");
        $maskBin = pack("H*", $mask);

        return ($ipBin & $maskBin) === ($subnetBin & $maskBin);
    }
}
