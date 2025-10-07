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
if(!(function_exists('printRequired'))){
    function printRequired($text = '*', $title = 'Required')
    {
        return "<small class='text-danger' title='".$title."' data-toggle='tooltip' data-placement='top'>".$text."</small>";
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
                'name'           => 'Donatur a.n ' . $donatur->name,
                'crm_pipeline_id'=> 1,
                'donatur_id'     => $donatur->id,
                'assign_to'      => 6,
                'description'    => 'Target menjadikan donatur tetap pada program internal',
                'nominal'        => 1000000,
                'is_potential'   => 1,
                'created_by'     => 6,
                'created_at'    => now(),
            ]);

            // Simpan ke CRMProspectLogs
            \App\Models\CRMProspectLogs::create([
                'pipeline_name'   => 'Leads',
                'crm_prospect_id' => $prospect->id,
                'crm_pipeline_id' => 1,
                'created_by'      => 6,
                'created_at'      => now(),
            ]);
        }
    }
}

if (!function_exists('checkSuspect')) {
    function checkSuspect($nominal, $deviceId, $uaCore, $ipAddress, $sessionId, $fingerprintId)
    {
        // Normalisasi input
        $deviceId      = trim((string) ($deviceId ?? '')) ?: null;
        $sessionId     = trim((string) ($sessionId ?? '')) ?: null;
        $uaCore        = trim((string) ($uaCore ?? '')) ?: null;
        $ipAddress     = trim((string) ($ipAddress ?? '')) ?: null;
        $fingerprintId = trim((string) ($fingerprintId ?? '')) ?: null;

        // Parameter waktu
        $windowHours  = 24;  // Waktu pemeriksaan
        $recentCancel = 5;   // Cancel < 5 menit
        $now = now();

        // Fungsi untuk log suspect
        $logSuspect = function ($tx, $reason) use ($deviceId, $uaCore, $ipAddress, $sessionId, $fingerprintId) {
            SpamLog::updateOrCreate(
                ['transaction_id' => $tx->id, 'reason' => $reason],
                [
                    'device_id'      => $deviceId,
                    'ua_core'        => $uaCore,
                    'ip_address'     => $ipAddress,
                    'session_id'     => $sessionId,
                    'fingerprint_id' => $fingerprintId,
                ]
            );

            if (!$tx->is_suspect) {
                $tx->update(['is_suspect' => 1]);
            }

            return [
                'is_suspect'     => 1,
                'invoice_number' => $tx->invoice_number,
            ];
        };

        /*
        |--------------------------------------------------------------------------
        | RULE 1 — Cek di SpamLog
        |--------------------------------------------------------------------------
        */
        $hasSpamHistory = SpamLog::where('ip_address', $ipAddress)
            ->where('created_at', '>=', $now->subHours($windowHours))
            ->exists();

        if ($hasSpamHistory) {
            $tx = Transaction::where('ip_address', $ipAddress)->latest()->first();
            if ($tx) {
                return $logSuspect($tx, 'IP previously flagged in spam_logs');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 2 — Cek IP address
        |--------------------------------------------------------------------------
        */
        if ($ipAddress) {
            $suspectByIp = Transaction::where('ip_address', $ipAddress)
                ->where('is_suspect', 1)
                ->where('created_at', '>=', $now->subHours($windowHours))
                ->latest('id')
                ->first();

            if ($suspectByIp) {
                return $logSuspect($suspectByIp, 'Same IP used within 24h');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 3 — Cek session_id
        |--------------------------------------------------------------------------
        */
        if ($sessionId) {
            $suspectBySession = Transaction::where('session_id', $sessionId)
                ->where('is_suspect', 1)
                ->where('created_at', '>=', $now->subHours($windowHours))
                ->latest('id')
                ->first();

            if ($suspectBySession) {
                return $logSuspect($suspectBySession, 'Same session used within 24h');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 4 — Cek device_id
        |--------------------------------------------------------------------------
        */
        if ($deviceId) {
            $suspectByDevice = Transaction::where('device_id', $deviceId)
                ->where('is_suspect', 1)
                ->where('created_at', '>=', $now->subHours($windowHours))
                ->latest('id')
                ->first();

            if ($suspectByDevice) {
                return $logSuspect($suspectByDevice, 'Same device used within 24h');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 5 — Cek fingerprint_id (opsional karena tidak stabil)
        |--------------------------------------------------------------------------
        */
        if ($fingerprintId) {
            $suspectByFp = Transaction::where('fingerprint_id', $fingerprintId)
                ->where('is_suspect', 1)
                ->where('created_at', '>=', $now->subHours($windowHours))
                ->latest('id')
                ->first();

            if ($suspectByFp) {
                return $logSuspect($suspectByFp, 'Same fingerprint used within 24h');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 6 — Cek kombinasi kecil: IP + nominal sama, tapi status cancel/draft
        |--------------------------------------------------------------------------
        */
        $cancelled = Transaction::where('ip_address', $ipAddress)
            ->whereIn('status', ['draft', 'cancel'])
            ->where('updated_at', '>=', $now->subMinutes($recentCancel))
            ->latest('id')
            ->first();

        if ($cancelled) {
            return $logSuspect($cancelled, 'Repeated donation attempt (cancel/draft)');
        }

        /*
        |--------------------------------------------------------------------------
        | RULE 7 — Tidak ada indikasi spam
        |--------------------------------------------------------------------------
        */
        return [
            'is_suspect'     => 0,
            'invoice_number' => '',
        ];
    }
}

