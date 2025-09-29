<?php

namespace App\Helpers;

class UserAgentHelper
{
    public static function parse($userAgentString)
    {
        // Pisahkan IP dan User Agent
        [$ip, $ua] = array_pad(explode(' | ', $userAgentString, 2), 2, '-');

        // Default values
        $device  = 'Unknown';
        $os      = 'Unknown';
        $browser = 'Unknown';

        // --- Device Detection ---
        if (preg_match('/Mobile|Android|iPhone|BlackBerry|Opera Mini/i', $ua)) {
            $device = 'Mobile';
        } elseif (preg_match('/Tablet|iPad/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/Windows|Macintosh|Linux/i', $ua)) {
            $device = 'Desktop';
        }

        // --- OS Detection ---
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

        // --- Browser Detection ---
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
}
