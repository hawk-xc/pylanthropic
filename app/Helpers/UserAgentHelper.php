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

    public static function parseCore1(string $ua): string
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
  
  
  
  
  
    public static function parseCore(string $ua): string
    {
        if (!$ua) return 'unknown_unknown_web';

        $rawUa = $ua;
        $ua = strtolower($ua);

        // ========= OS DETECTION =========
        $os = 'unknown';

        // Android: android 14, 13, dst
        if (preg_match('/android\s+([0-9]+)(?:[\._][0-9]+)*/', $ua, $m)) {
            $os = 'android' . (int)$m[1];

        // iPhone: iPhone OS 16_7_2, iOS 16_7, dst
        } elseif (preg_match('/iphone os\s+([0-9_]+)/', $ua, $m)) {
            $os = 'ios' . str_replace('_', '.', $m[1]);

        // iPad: CPU OS 17_0, iPad; CPU OS 15_6 like Mac OS X
        } elseif (preg_match('/(?:ipad;.*cpu os|cpu os)\s+([0-9_]+)/', $ua, $m)) {
            $os = 'ios' . str_replace('_', '.', $m[1]);

        // macOS: Mac OS X 10_15_7, 12_6, 13_5
        } elseif (preg_match('/mac os x\s+([0-9_]+)/', $ua, $m)) {
            $os = 'macos' . str_replace('_', '.', $m[1]);

        // Windows: Windows NT 10.0, 6.3, 6.1, 11 sometimes masks as 10.0
        } elseif (preg_match('/windows nt\s+([0-9\.]+)/', $ua, $m)) {
            $nt = $m[1];
            // Map NT → marketing version (best-effort)
            $map = [
                '10.0' => 'windows10', // bisa Windows 10/11; NT 10.0 dipakai untuk keduanya
                '6.3'  => 'windows8.1',
                '6.2'  => 'windows8',
                '6.1'  => 'windows7',
                '6.0'  => 'windowsvista',
                '5.1'  => 'windowsxp',
            ];
            $os = $map[$nt] ?? ('windows' . preg_replace('/[^0-9]/', '', $nt));

        // ChromeOS: CrOS x86_64 14541.0.0
        } elseif (preg_match('/cros\s+[^ ]+\s+([0-9\.]+)/', $ua, $m)) {
            $os = 'chromeos' . $m[1];

        // HarmonyOS (Huawei)
        } elseif (preg_match('/harmonyos\s*([0-9\.]+)/', $ua, $m)) {
            $os = 'harmonyos' . $m[1];

        // Linux generic
        } elseif (str_contains($ua, 'linux')) {
            $os = 'linux';
        }

        // ========= MODEL / DEVICE =========
        $model = 'unknown';

        // Android model: "; SM-A546E Build/", "; sdk_gphone64_arm64 Build/", "; Redmi Note 8 Pro)"
        if (preg_match('/;\s*([a-z0-9 _\-\/]+?)\s*build\//', $ua, $m)) {
            $model = strtoupper(self::cleanModel($m[1]));
        } elseif (preg_match('/;\s*([a-z0-9 _\-\/]+?)\s*\)/', $ua, $m) && str_contains($ua, 'android')) {
            // fallback android pattern before ')'
            $candidate = strtoupper(self::cleanModel($m[1]));
            // Hindari kata generik
            if (!preg_match('/^(linux|android|u;|en-us|ru|id)$/i', $candidate)) {
                $model = $candidate;
            }
        } elseif (str_contains($ua, 'sdk_gphone')) {
            $model = 'SDK_GPHONE'; // emulator
        } elseif (str_contains($ua, 'iphone')) {
            $model = 'IPHONE';
        } elseif (str_contains($ua, 'ipad')) {
            $model = 'IPAD';
        } elseif (str_contains($ua, 'macintosh')) {
            $model = 'MAC';
        } elseif (str_contains($ua, 'windows')) {
            $model = 'PC';
        } elseif (str_contains($ua, 'cros')) {
            $model = 'CHROMEBOOK';
        }

        // ========= APP / CONTAINER (in-app browser / webview / browser family) =========
        // default web
        $app = 'web';

        // In-App priority
        if (str_contains($ua, 'fb_iab') || str_contains($ua, 'fbav')) {
            $app = 'fb';
        } elseif (str_contains($ua, 'instagram')) {
            $app = 'ig';
        } elseif (str_contains($ua, 'tiktok')) {
            $app = 'tt';
        } elseif (str_contains($ua, 'line')) {
            $app = 'line';
        } elseif (str_contains($ua, 'whatsapp')) {
            $app = 'wa';
        } elseif (str_contains($ua, 'telegram')) {
            $app = 'tg';
        } elseif (str_contains($ua, 'twitter') || str_contains($ua, 'x-twitter')) {
            $app = 'tw';
        } elseif (preg_match('/\bwv\b/', $ua) || str_contains($ua, 'version/') && str_contains($ua, ' mobile safari/')) {
            // banyak WebView android menyertakan "wv" atau "Version/x Mobile Safari"
            $app = 'wv';
        } else {
            // (Opsional) tandai browser family kalau mau:
            if (str_contains($ua, 'edg/')) {
                $app = 'edge';
            } elseif (str_contains($ua, 'opr/')) {
                $app = 'opera';
            } elseif (str_contains($ua, 'firefox/')) {
                $app = 'firefox';
            } elseif (str_contains($ua, 'safari/') && !str_contains($ua, 'chrome/')) {
                // Safari asli (macOS/iOS) biasanya punya "Version/x Safari/"
                $app = 'safari';
            } elseif (str_contains($ua, 'chrome/')) {
                $app = 'chrome';
            }
            // kalau gak kedetek, tetap 'web'
        }

        return "{$os}_{$model}_{$app}";
    }

    // Helper untuk merapikan model (hapus noise)
    private static function cleanModel(string $s): string
    {
        // buang token umum yg bukan model
        $s = preg_replace('/\b(android|linux|u;|en-us|like mac os x|mobile|tablet)\b/i', '', $s);
        // rapikan spasi & tanda
        $s = trim(preg_replace('/\s+/', ' ', $s));
        // hilangkan trailing titik/koma/semicolon
        $s = rtrim($s, ' .;,_-');
        // ganti spasi jadi underscore agar konsisten dengan format lama
        $s = str_replace(' ', '_', $s);
        return $s;
    }

}

