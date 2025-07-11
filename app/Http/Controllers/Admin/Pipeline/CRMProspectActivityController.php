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
            'prospect_id' => 'required|string',
            'type_name' => 'required|string|in:wa,sms,email,call,meeting,note,task',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'date_time' => 'required',
        ], [
            'prospect_id.required' => 'ID Prospek harus diisi',
            'type_name.required' => 'Tipe harus diisi',
            'type_name.in' => 'Tipe harus berupa wa, sms, email, call, meeting, note, atau task',
            'content.string' => 'Konten harus berupa string',
            'description.string' => 'Deskripsi harus berupa string',
            'date_time.required' => 'Tanggal harus diisi',
            'date_time.datetime' => 'Tanggal harus berupa tanggal dan waktu yang valid',
        ]);

        try {
            $crmProspectActivity = new CRMProspectActivity;
            $crmProspectActivity->crm_prospect_id = $request->prospect_id;
            $crmProspectActivity->type = $request->type_name;
            $crmProspectActivity->content = $request->content;
            $crmProspectActivity->description = $request->description;
            $crmProspectActivity->date = $request->date_time;
            $crmProspectActivity->created_by = auth()->user()->id;

            $crmProspectActivity->save();

            return back()->with('message', [
                'type' => 'success',
                'text' => 'Berhasil menambah data Aktifitas Prospek!',
            ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'type' => 'error',
                'text' => $err->getMessage()
            ]);
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
                    'success' => false,
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
