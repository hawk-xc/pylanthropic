<?php

use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\SpamLog;

/**
 * Show required state
 *
 * @param string $text
 * @param string $title
 * @return string
 */
if (!function_exists('printRequired')) {
    function printRequired($text = '*', $title = 'Required')
    {
        return "<small class='text-danger' title='" . $title . "' data-toggle='tooltip' data-placement='top'>" . $text . '</small>';
    }
}

if (!function_exists('importProspectDonatur')) {
    /**
     * Filter donatur lalu simpan ke Prospect dan Prospect Logs
     *
     * @return void
     */
    function importProspectDonatur()
    {
        // Ambil donatur sesuai kriteria
        $donaturs = \App\Models\Donatur::with(['chat', 'transaction', 'donaturLoyal'])
            ->whereNull('wa_inactive_since')
            ->where('want_to_contact', 1)
            ->where('sum_donate_paid', '>=', 500000)
            ->where('count_donate_paid', '>', 2)
            ->orderBy('count_donate_paid', 'DESC')
            ->get();

        foreach ($donaturs as $donatur) {
            // Simpan ke CRMProspect
            $prospect = \App\Models\CRMProspect::create([
                'name' => 'Donatur a.n ' . $donatur->name,
                'crm_pipeline_id' => 1,
                'donatur_id' => $donatur->id,
                'assign_to' => 6,
                'description' => 'Target menjadikan donatur tetap pada program internal',
                'nominal' => 1000000,
                'is_potential' => 1,
                'created_by' => 6,
                'created_at' => now(),
            ]);

            // Simpan ke CRMProspectLogs
            \App\Models\CRMProspectLogs::create([
                'pipeline_name' => 'Leads',
                'crm_prospect_id' => $prospect->id,
                'crm_pipeline_id' => 1,
                'created_by' => 6,
                'created_at' => now(),
            ]);
        }
    }
}

if (!function_exists('checkSuspect')) {
    function checkSuspect($nominal, $deviceId, $uaCore, $ipAddress, $sessionId, $fingerprintId, $userAgent = null, $donaturName = null)
    {
        // Normalisasi input
        $deviceId = trim((string) ($deviceId ?? '')) ?: null;
        $sessionId = trim((string) ($sessionId ?? '')) ?: null;
        $uaCore = trim((string) ($uaCore ?? '')) ?: null;
        $ipAddress = trim((string) ($ipAddress ?? '')) ?: null;
        $fingerprintId = trim((string) ($fingerprintId ?? '')) ?: null;
        
        $userAgent     = trim((string) ($userAgent ?? '')) ?: null;
        $donaturName   = trim((string) ($donaturName ?? '')) ?: null;

        Log::info('new donation record: ' . $nominal . ' - ' . $deviceId . ' - ' . $uaCore . ' - ' . $ipAddress . ' - ' . $sessionId . ' - ' . $fingerprintId . ' - ' . $userAgent . ' - ' . $donaturName);

        $limitNominal        = 10000;
        $cancelFreshMinutes  = 5;
        $dayWindow           = 5;      // 5 days
        $recentWindowMinutes = 320;

        /*
        |--------------------------------------------------------------------------
        | RULE 0: Terlalu sering transaksi dalam 5 hari (device/session/fingerprint)
        |--------------------------------------------------------------------------
        */
        $window = now()->subDays($dayWindow);

        $countRecentTx = App\Models\Transaction::query()
            ->where('created_at', '>=', $window)
            ->where('status', '!=', 'success')
            ->where(function ($q) use ($deviceId, $sessionId, $fingerprintId) {
                $conds = 0;
                if ($deviceId) {
                    $q->orWhere('device_id', $deviceId);
                    $conds++;
                }
                if ($sessionId) {
                    $q->orWhere('session_id', $sessionId);
                    $conds++;
                }
                if ($fingerprintId) {
                    $q->orWhere('fingerprint_id', $fingerprintId);
                    $conds++;
                }

                // Jika semua null, tambahkan kondisi false agar tidak match semua record
                if ($conds === 0) {
                    $q->whereRaw('1=0');
                }
            })
            ->count();

        if ($countRecentTx >= 2) {
            $recentTx = App\Models\Transaction::query()
                ->where('created_at', '>=', $window)
                ->where('status', '!=', 'success')
                ->where(function ($q) use ($deviceId, $sessionId, $fingerprintId) {
                    if ($deviceId) {
                        $q->orWhere('device_id', $deviceId);
                    }
                    if ($sessionId) {
                        $q->orWhere('session_id', $sessionId);
                    }
                    if ($fingerprintId) {
                        $q->orWhere('fingerprint_id', $fingerprintId);
                    }
                })
                ->latest('id')
                ->first();

            if ($recentTx) {
                $recentTx->update(['is_suspect' => 1]);

                App\Models\SpamLog::updateOrCreate(
                    ['transaction_id' => $recentTx->id, 'reason' => 'too many transactions within 5 days'],
                    [
                        'device_id' => $deviceId,
                        'ua_core' => $uaCore,
                        'user_agent' => $userAgent,
                        'ip_address' => $ipAddress,
                        'session_id' => $sessionId,
                        'fingerprint_id' => $fingerprintId,
                    ],
                );

                return [
                    'is_suspect' => 1,
                    'invoice_number' => $recentTx->invoice_number,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 1: Cek fingerprintId lebih dulu
        |--------------------------------------------------------------------------
        */
        if (!empty($fingerprintId)) {
            $suspectTxForFp = App\Models\Transaction::where('fingerprint_id', $fingerprintId)
                ->where('status', '!=', 'success')
                ->where('is_suspect', 1)
                ->where('created_at', '>=', now()->subDays($dayWindow))
                ->get();

            if ($suspectTxForFp->isNotEmpty()) {
                $matching = $suspectTxForFp->first();

                App\Models\SpamLog::updateOrCreate(
                    [
                        'transaction_id' => $matching->id,
                        'reason' => 'fingerprint previously suspect',
                    ],
                    [
                        'device_id' => $deviceId,
                        'ua_core' => $uaCore,
                        'user_agent' => $userAgent,
                        'ip_address' => $ipAddress,
                        'session_id' => $sessionId,
                        'fingerprint_id' => $fingerprintId,
                    ],
                );

                return [
                    'is_suspect' => 1,
                    'invoice_number' => $matching->invoice_number,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 2: Cek IP sebagai fallback (jika fingerprint belum pernah tersimpan)
        |--------------------------------------------------------------------------
        */
        if (!empty($ipAddress)) {
            $suspectTxForIp = App\Models\Transaction::where('ip_address', $ipAddress)
                ->where('status', '!=', 'success')
                ->where('is_suspect', 1)
                ->where('created_at', '>=', now()->subDays($dayWindow))
                ->get();

            if ($suspectTxForIp->isNotEmpty()) {
                $matching = $suspectTxForIp->first(function ($t) use ($deviceId, $sessionId, $uaCore, $fingerprintId, $userAgent) {
                    if ($fingerprintId && $t->fingerprint_id === $fingerprintId) {
                        return true;
                    }
                    if ($deviceId && $t->device_id === $deviceId) {
                        return true;
                    }
                    if ($sessionId && $t->session_id === $sessionId) {
                        return true;
                    }
                    if ($uaCore && $t->ua_core === $uaCore) {
                        return true;
                    }
                    if ($userAgent && $t->user_agent === $userAgent) {
                        return true;
                    }
                    return false;
                });

                if ($matching) {
                    App\Models\SpamLog::updateOrCreate(
                        ['transaction_id' => $matching->id, 'reason' => 'daily limit after suspect (ip fallback)'],
                        [
                            'device_id' => $deviceId,
                            'ua_core' => $uaCore,
                            'user_agent' => $userAgent,
                            'ip_address' => $ipAddress,
                            'session_id' => $sessionId,
                            'fingerprint_id' => $fingerprintId,
                        ],
                    );

                    return [
                        'is_suspect' => 1,
                        'invoice_number' => $matching->invoice_number,
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 3: Duplicate big donation (cek fingerprint dulu)
        |--------------------------------------------------------------------------
        */
        if ($nominal >= $limitNominal) {
            // 3a. Cek fingerprint
            if ($fingerprintId) {
                $recentByFp = App\Models\Transaction::where('fingerprint_id', $fingerprintId)
                    ->where('status', '!=', 'success')
                    ->where(function ($q) use ($cancelFreshMinutes) {
                        $q->where('status', 'draft')->orWhere(function ($q2) use ($cancelFreshMinutes) {
                            $q2->where('status', 'cancel')->where('updated_at', '>=', now()->subMinutes($cancelFreshMinutes));
                        });
                    })
                    ->where('nominal', $nominal)
                    ->where('created_at', '>=', now()->subMinutes($recentWindowMinutes))
                    ->latest('id')
                    ->first();

                if ($recentByFp) {
                    if (!$recentByFp->is_suspect) {
                        $recentByFp->update(['is_suspect' => 1]);
                    }

                    App\Models\SpamLog::updateOrCreate(
                        ['transaction_id' => $recentByFp->id, 'reason' => 'duplicate big donation (fingerprint)'],
                        [
                            'device_id' => $deviceId,
                            'ua_core' => $uaCore,
                            'user_agent' => $userAgent,
                            'ip_address' => $ipAddress,
                            'session_id' => $sessionId,
                            'fingerprint_id' => $fingerprintId,
                        ],
                    );

                    return [
                        'is_suspect' => 1,
                        'invoice_number' => $recentByFp->invoice_number,
                    ];
                }
            }

            // 3b. Jika fingerprint tidak ada, pakai IP fallback
            if ($ipAddress) {
                $recentByIp = App\Models\Transaction::where('ip_address', $ipAddress)
                    ->where('status', '!=', 'success')
                    ->where(function ($q) use ($cancelFreshMinutes) {
                        $q->where('status', 'draft')->orWhere(function ($q2) use ($cancelFreshMinutes) {
                            $q2->where('status', 'cancel')->where('updated_at', '>=', now()->subMinutes($cancelFreshMinutes));
                        });
                    })
                    ->where('nominal', $nominal)
                    ->where('created_at', '>=', now()->subMinutes($recentWindowMinutes))
                    ->latest('id')
                    ->first();

                if ($recentByIp) {
                    if (!$recentByIp->is_suspect) {
                        $recentByIp->update(['is_suspect' => 1]);
                    }

                    App\Models\SpamLog::updateOrCreate(
                        ['transaction_id' => $recentByIp->id, 'reason' => 'duplicate big donation (ip fallback)'],
                        [
                            'device_id' => $deviceId,
                            'ua_core' => $uaCore,
                            'user_agent' => $userAgent,
                            'ip_address' => $ipAddress,
                            'session_id' => $sessionId,
                            'fingerprint_id' => $fingerprintId,
                        ],
                    );

                    return [
                        'is_suspect' => 1,
                        'invoice_number' => $recentByIp->invoice_number,
                    ];
                }
            }
        }

        // Default aman
        return ['is_suspect' => 0, 'invoice_number' => ''];
    }
}
