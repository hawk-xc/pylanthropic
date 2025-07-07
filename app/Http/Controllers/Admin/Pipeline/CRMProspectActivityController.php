<?php

namespace App\Http\Controllers\Admin\Pipeline;

use Exception;
use App\Models\CRMPipeline;
use Illuminate\Http\Request;
use App\Models\CRMProspectActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class CRMProspectActivityController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get all pipelines data
        $pipelines = CRMPipeline::select(['id', 'type'])->get();

        return view('admin.crm-prospect-activity.create', ['pipelines' => $pipelines]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pipeline' => 'required|numeric',
            'type' => 'required|in:wa,sms,email,call,meeting,note,task',
            'content' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
        ], [
            'pipeline.required' => 'Pipeline wajib diisi.',
            'pipeline.numeric' => 'Pipeline harus berupa angka.',
            'type.required' => 'Tipe wajib diisi.',
            'type.in' => 'Tipe harus berupa WA, SMS, Email, Call, Meeting, Note, atau Task.',
            'content.required' => 'Konten wajib diisi.',
            'content.string' => 'Konten harus berupa teks.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Tanggal harus berupa tanggal yang valid.',
        ]);

        try {
            $prospect_activity = new CRMProspectActivity;

            $prospect_activity->crm_prospect_id = $request->pipeline;
            $prospect_activity->type = $request->type;
            $prospect_activity->content = $request->content;
            $prospect_activity->description = $request->description;
            $prospect_activity->date = $request->date;

            $prospect_activity->created_by = auth()->user()->id;

            $prospect_activity->save();
            
            $pipelines_name = CRMPipeline::where('id', $request->pipeline)->first()->type;

            return redirect()->to('adm/crm-pipeline?type=' . $pipelines_name)->with('success', 'Data berhasil ditambahkan');
        } catch (Exception $err) {
            dd($err);
            return back()->with('error', $err->getMessage());
        }
    }

    public function listProspectActivity(Request $request) 
    {
        $prospect_type = $request->pipeline;

        $prospectActivity = CRMPipeline::where('type', $prospect_type)->first();

        try {
            if ($prospectActivity) {
                $prospectActivity->load(['crm_prospect.crm_prospect_activity']);

                return response()->json(
                    [
                        'success' => true,
                        'data' => $prospectActivity,
                    ],
                    200,
                );
            }
        } catch (Exception $err) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $err,
                ],
                403,
            );
        }
    }

    public function updateActivityStage(Request $request, $activityId)
    {
        try {
            $request->validate([
                'new_prospect_id' => 'required|string',
            ]);

            $activity = CRMProspectActivity::findOrFail($activityId);
            $activity->update([
                'crm_prospect_id' => $request->new_prospect_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity stage berhasil diupdate',
                'data' => $activity,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate activity: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getActivityInfo(Request $request)
    {
        $activityId = $request->id;

        try {
            $data = CRMProspectActivity::with(['prospect', 'prospect.pipeline'])
                ->findOrFail($activityId);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data activity',
                'data' => $data,
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data activity' . $err->getMessage(),
            ], 500);
        }
    }
}
