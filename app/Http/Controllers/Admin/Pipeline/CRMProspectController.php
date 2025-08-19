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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $leads_name = $request->leads_id;

        $pipelines = CRMLeads::where('name', 'like', '%' . $leads_name . '%')->first()->crm_pipelines;

        return view('admin.crm-prospect.create', ['pipelines' => $pipelines]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
                'pipeline' => 'required|numeric',
                'prospect_type' => 'required|in:donatur,organization,grab_organization',
                'prospect_id' => 'required|numeric',
                'assign_to' => 'required|numeric',
                'description' => 'required|string',
                'nominal' => 'required_if:prospect_type,donatur|nullable|string',
                'is_potential' => 'required|in:1,0',
            ],
            [
                'name.required' => 'Nama prospect harus diisi.',
                'pipeline.required' => 'Pipeline harus dipilih.',
                'prospect_type.required' => 'Tipe prospect harus dipilih.',
                'prospect_type.in' => 'Tipe prospect tidak valid.',
                'prospect_id.required' => 'Id Data harus dipilih.',
                'assign_to.required' => 'Assign to harus dipilih.',
                'description.required' => 'Deskripsi harus diisi.',
                'nominal.required_if' => 'Nominal harus diisi jika prospect type adalah donatur.',
                'is_potential.required' => 'Status potential harus dipilih.',
                'is_potential.in' => 'Nilai status potential tidak valid.',
            ],
        );
        
        try {
            $prospect = new CRMProspect();
            $prospect->name = $request->name;
            $prospect->crm_pipeline_id = $request->pipeline;
            
            switch ($request->prospect_type) {
                case 'donatur':
                    $prospect->donatur_id = $request->prospect_id;
                    break;
                case 'organization':
                    $prospect->organization_id = $request->prospect_id;
                    break;
                case 'grab_organization':
                    $prospect->grab_organization_id = $request->prospect_id;
                    break;
                default:
                    $prospect->donatur_id = $request->prospect_id;
                    break;
            }

            // get prospect type
            $prospect->prospect_type = $request->prospect_type;

            $prospect->assign_to = $request->assign_to;
            $prospect->description = $request->description;
            $prospect->nominal = $request->nominal ? str_replace('.', '', $request->nominal) : 0;
            $prospect->is_potential = $request->is_potential;

            $prospect->created_by = auth()->user()->id;

            $prospect->save();

            $prospect_logs = new CRMProspectLogs();
            $prospect_logs->pipeline_name = CRMPipeline::findOrFail($request->pipeline)->name;
            $prospect_logs->crm_prospect_id = $prospect->id;
            $prospect_logs->crm_pipeline_id = $request->pipeline;
            $prospect_logs->created_by = auth()->user()->id;
            $prospect_logs->save();

            $pipeline_name = CRMPipeline::where('id', $request->pipeline)->first()->crm_lead->name;

            return redirect()
                ->to('adm/crm-pipeline?leads=' . $pipeline_name)
                ->with('message', [
                    'status' => 'success',
                    'message' => 'Berhasil menambah data Prospek!',
                ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'status' => 'error',
                'message' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $crm_prospect = CRMProspect::findOrFail($id);
        $crm_prospect_pic = User::findOrFail($crm_prospect->assign_to);

        return view('admin.crm-prospect.show', ['crm_prospect' => $crm_prospect, 'crm_prospect_pic' => $crm_prospect_pic]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $leads_name = $request->leads;

        $pipelines = CRMLeads::where('name', 'like', '%' . $leads_name . '%')->first()->crm_pipelines;

        $crm_prospect = CRMProspect::with(['crm_prospect_donatur', 'crm_prospect_organization', 'crm_prospect_grab_organization', 'crm_prospect_pic'])->findOrFail($id);

        $prospectId = '';
        $prospectText = '';
        switch ($crm_prospect->prospect_type) {
            case 'donatur':
                $prospectId = $crm_prospect->donatur_id;
                if ($crm_prospect->crm_prospect_donatur) {
                    $prospectText = $crm_prospect->crm_prospect_donatur->name . ' (' . $crm_prospect->crm_prospect_donatur->telp . ')';
                }
                break;
            case 'organization':
                $prospectId = $crm_prospect->organization_id;
                $prospectText = optional($crm_prospect->crm_prospect_organization)->name ?? '';
                break;
            case 'grab_organization':
                $prospectId = $crm_prospect->grab_organization_id;
                $prospectText = optional($crm_prospect->crm_prospect_grab_organization)->name ?? '';
                break;
        }

        return view('admin.crm-prospect.edit', [
            'crm_prospect' => $crm_prospect,
            'pipelines' => $pipelines,
            'leads_name' => $leads_name,
            'prospectId' => $prospectId,
            'prospectText' => $prospectText,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $leads_name = $request->leads;

        $request->validate(
            [
                'name' => 'required|string',
                'pipeline' => 'required|numeric',
                'prospect_type' => 'required|in:donatur,organization,grab_organization',
                'prospect_id' => 'required|numeric',
                'assign_to' => 'required|numeric',
                'description' => 'nullable|string',
                'nominal' => 'required_if:prospect_type,donatur|nullable|string',
                'is_potential' => 'required|in:1,0',
            ],
            [
                'name.required' => 'Nama prospect harus diisi.',
                'pipeline.required' => 'Pipeline harus dipilih.',
                'prospect_type.required' => 'Tipe prospect harus dipilih.',
                'prospect_type.in' => 'Tipe prospect tidak valid.',
                'prospect_id.required' => 'Id Data harus dipilih.',
                'assign_to.required' => 'Assign to harus dipilih.',
                'nominal.required_if' => 'Nominal harus diisi jika prospect type adalah donatur.',
                'is_potential.required' => 'Status potential harus dipilih.',
                'is_potential.in' => 'Nilai status potential tidak valid.',
            ],
        );

        try {
            $prospect = CRMProspect::findOrFail($id);
            $prospect->name = $request->name;
            $prospect->crm_pipeline_id = $request->pipeline;

            $prospect->donatur_id = null;
            $prospect->organization_id = null;
            $prospect->grab_organization_id = null;

            switch ($request->prospect_type) {
                case 'donatur':
                    $prospect->donatur_id = $request->prospect_id;
                    break;
                case 'organization':
                    $prospect->organization_id = $request->prospect_id;
                    break;
                case 'grab_organization':
                    $prospect->grab_organization_id = $request->prospect_id;
                    break;
            }

            $prospect->prospect_type = $request->prospect_type;
            $prospect->assign_to = $request->assign_to;
            $prospect->description = $request->description;
            $prospect->nominal = $request->nominal ? str_replace('.', '', $request->nominal) : 0;
            $prospect->is_potential = $request->is_potential;

            $prospect->updated_by = auth()->user()->id;
            $prospect->save();

            return redirect()
                ->to('adm/crm-prospect/' . $id . '?leads=' . $leads_name)
                ->with('message', [
                    'status' => 'success',
                    'message' => 'Berhasil mengubah data Prospek!',
                ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'status' => 'error',
                'message' => $err->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leadsId = request()->input('leads') ?? request()->query('leads');

        try {
            $prospect = CRMProspect::findOrFail($id);
            $prospect->delete();

            return redirect()
                ->route('adm.crm-leads.index', ['leads' => $leadsId])
                ->with('message', [
                    'status' => 'success',
                    'message' => 'Data Prospect berhasil dihapus!',
                ]);
        } catch (Exception $err) {
            return redirect()
                ->back()
                ->with('message', [
                    'status' => 'error',
                    'message' => 'Gagal menghapus data' + $err->getMessage(),
                ]);
        }
    }

    public function listProspects(Request $request)
    {
        $prospect_status = $request->pipeline;

        try {
            $prospects = CRMPipeline::where('status', $prospect_status)->first();

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
                $prospect_logs = new CRMProspectLogs();
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
