<?php

namespace App\Http\Controllers\Admin\Pipeline;

use Exception;
use App\Models\CRMPipeline;
use App\Models\CRMLeads;
use App\Models\CRMProspect;
use App\Models\CRMProspectLogs;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CRMProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $leads_name = $request->leads;

        $pipelines = CRMLeads::where('name', 'like', '%' . $leads_name . '%')
            ->first()->crm_pipelines;

        return view('admin.crm-prospect.create', ['pipelines' => $pipelines]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'pipeline' => 'required|numeric',
            'donatur' => 'required|numeric',
            'assign_to' => 'required|numeric',
            'description' => 'required|string',
            'nominal' => 'required|string',
            'is_potential' => 'required|in:1,0',
        ], [
            'name.required' => 'Nama prospect harus diisi.',
            'pipeline.required' => 'Pipeline harus dipilih.',
            'donatur.required' => 'Donatur harus dipilih.',
            'assign_to.required' => 'Assign to harus dipilih.',
            'description.required' => 'Deskripsi harus diisi.',
            'nominal.required' => 'Nominal harus diisi.',
            'is_potential.required' => 'Status potential harus dipilih.',
            'is_potential.in' => 'Nilai status potential tidak valid.',
        ]);

        try {
            $prospect = new CRMProspect;
            $prospect->name = $request->name;
            $prospect->crm_pipeline_id = $request->pipeline;
            $prospect->donatur_id = $request->donatur;
            $prospect->assign_to = $request->assign_to;
            $prospect->description = $request->description;
            $prospect->nominal = str_replace('.', '', $request->nominal);
            $prospect->is_potential = $request->is_potential;
            
            $prospect->created_by = auth()->user()->id;
            
            $prospect->save();

            $pipeline_name = CRMPipeline::where('id', $request->pipeline)->first()->crm_lead->name;

            return redirect()->to('adm/crm-pipeline?leads=' . $pipeline_name)->with('success', 'Data berhasil ditambahkan');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $crm_prospect = CRMProspect::findOrFail($id);
        $crm_prospect_pic = User::findOrFail($crm_prospect->id);

        return view('admin.crm-prospect.show', ['crm_prospect' => $crm_prospect, 'crm_prospect_pic' => $crm_prospect_pic]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function listProspects(Request $request) 
    {
        $prospect_type = $request->pipeline;

        try {
            $prospects = CRMPipeline::where('type', $prospect_type)->first();

            if ($prospects) {
                return response()->json(
                    [
                        'success' => true,
                        'data' => $prospects->crm_prospect,
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'data' => 'data not found',
                    ],
                    403,
                );
            }

        } catch (Exception $err) {
            response()->json(
                [
                    'success' => true,
                    'message' => $err,
                ],
                400,
            );
        }
    }

    public function updatePipeline(Request $request, string $prospectId) 
    {                
        try {
            $prospect = CRMProspect::find($prospectId);

            if ($prospect) {
                $prospect->crm_pipeline_id = $request->new_pipeline_id;
                $prospect->save();

                // prospect logs
                $prospect_logs = new CRMProspectLogs;
                $prospect_logs->pipeline_name = CRMPipeline::findOrFail($request->new_pipeline_id)->name;
                $prospect_logs->crm_prospect_id = $prospectId;
                $prospect_logs->crm_pipeline_id = $request->new_pipeline_id;
                $prospect_logs->created_by = auth()->user()->id;
                
                $prospect_logs->save();

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Pipeline berhasil diupdate',
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Data prospect tidak ditemukan',
                    ],
                    403,
                );
            }
        } catch (Exception $err) {
            response()->json(
                [
                    'success' => true,
                    'message' => $err,
                ],
                400,
            );
        }
    }
}
