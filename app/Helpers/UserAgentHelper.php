<?php

namespace App\Helpers;

namespace App\Helpers;

class UserAgentHelper
{
    public static function parse($userAgentString)
    {
        [$ip, $ua] = array_pad(explode(' | ', $userAgentString, 2), 2, '-');

        $device  = 'Unknown';
        $os      = 'Unknown';
        $browser = 'Unknown';

        if (preg_match('/Mobile|Android|iPhone|BlackBerry|Opera Mini/i', $ua)) {
            $device = 'Mobile';
        } elseif (preg_match('/Tablet|iPad/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/Windows|Macintosh|Linux/i', $ua)) {
            $device = 'Desktop';
        }

        if (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/Windows NT/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Macintosh/i', $ua)) {
            $os = 'MacOS';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        }

        if (preg_match('/Chrome/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $ua) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/MSIE|Trident/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Edge/i', $ua)) {
            $browser = 'Edge';
        }

        return [
            'ip'      => trim($ip),
            'device'  => $device,
            'os'      => $os,
            'browser' => $browser,
            'raw'     => $ua
        ];
    }

    public static function parseCore(string $ua): string
    {
        $ua = strtolower($ua);

        $os = 'unknown';
        if (preg_match('/android\s([\d\.]+)/', $ua, $m)) {
            $os = 'android' . (int)$m[1];
        } elseif (preg_match('/iphone os\s([\d_]+)/', $ua, $m)) {
            $os = 'ios' . str_replace('_','.', $m[1]);
        }

        $model = 'unknown';
        if (preg_match('/;\s?([a-z0-9\- ]+)\sbuild/', $ua, $m)) {
            $model = strtoupper(str_replace(' ', '', $m[1]));
        } elseif (str_contains($ua, 'iphone')) {
            $model = 'IPHONE';
        }

        $app = 'web';
        if (str_contains($ua, 'fb_iab')) {
            $app = 'fb';
        } elseif (str_contains($ua, 'instagram')) {
            $app = 'ig';
        } elseif (str_contains($ua, 'wv')) {
            $app = 'wv';
        }

        return "{$os}_{$model}_{$app}";
    }
}

