<?php

use Illuminate\Support\Facades\Log;


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
