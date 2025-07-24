<?php

namespace App\Http\Controllers\Admin\Pipeline;

use Exception;
use App\Models\CRMLeads;
use App\Models\CRMPipeline;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CRMPipelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.crm-leads.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.crm-pipeline.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'leads_id' => 'required|numeric',
            'percentage_deals' => 'required|string',
            'description' => 'required|string',
            'is_active' => 'required|in:1,0'
        ], [
            'name.required' => 'Nama pipeline wajib diisi',
            'name.string' => 'Nama pipeline harus berupa teks',
            'leads_id.numeric' => 'id leads tidak valid',
            'percentage_deals.string' => 'Persentase deal harus berupa teks',
            'description.required' => 'Deskripsi wajib diisi',
            'description.string' => 'Deskripsi harus berupa teks',
            'is_active.required' => 'Status wajib diisi',
            'is_active.in' => 'Status tidak valid',
        ]);

        try {
           $data = new CRMPipeline;
           $data->name = $request->name;
           $data->crm_leads_id = $request->leads_id;
           $data->description = $request->description;
           $data->percentage_deals = $request->percentage_deals;
           $data->is_active = $request->is_active ? 1 : 0;

           $lastSortNumber = CRMPipeline::max('sort_number');
           $data->sort_number = $lastSortNumber !== null ? $lastSortNumber + 1 : 1;

           // user id
           $data->created_by = auth()->user()->id;

           if ($data->save()) {
            $leads_name = strtolower(CRMLeads::find($request->leads_id)->name);
            return redirect()->to('adm/crm-pipeline?leads=' . $leads_name)->with('message', [
                'type' => 'success',
                'text' => 'Berhasil menambah Data prospek!',
            ]);
           }

           return back()->with('message', [
                'type' => 'success',
                'text' => 'Berhasil menambah Data prospek!',
            ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'type' => 'error',
                'text' => $err->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pipeline = CRMPipeline::findOrFail($id);

        return view('admin.crm-pipeline.edit', [
            'pipeline' => $pipeline
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'leads_id' => 'required|numeric',
            'percentage_deals' => 'required|string',
            'description' => 'required|string',
        ], [
            'name.required' => 'Nama pipeline wajib diisi',
            'name.string' => 'Nama pipeline harus berupa teks',
            'leads_id.numeric' => 'id leads tidak valid',
            'percentage_deals.string' => 'Persentase deal harus berupa teks',
            'description.required' => 'Deskripsi wajib diisi',
            'description.string' => 'Deskripsi harus berupa teks',
        ]);

        try {
           $data = CRMPipeline::findOrFail($id);
           $data->name = $request->name;
           $data->crm_leads_id = $request->leads_id;
           $data->description = $request->description;
           $data->percentage_deals = $request->percentage_deals;
           $data->is_active = $request->is_active ? 1 : 0;

           // user id
           $data->created_by = auth()->user()->id;

           $data->save();

           return back()->with('message', [
                'status' => 'success',
                'message' => 'Berhasil update Data Pipeline!',
            ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'status' => 'error',
                'message' => $err->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pipeline = CRMPipeline::findOrFail($id);

        try {
            $pipeline->delete();
            $leads_name = strtolower(CRMLeads::find($id)->name);

            return redirect()->to('/adm/crm-leads?leads=' . $leads_name)->with('message', [
                'type' => 'success',
                'text' => 'Berhasil menghapus data Pipeline!'
            ]);
        } catch (Exception $err) {
            return back()->with('message', [
                'type' => 'error',
                'text' => 'Terjadi kesalahan: ' . $err->getMessage()
            ]);
        }
    }

    public function listAllPipelines()
    {
        $query = CRMPipeline::query();

        $query->where('is_active', 1);

        return Datatables::of($query)
            ->addColumn('type', function ($row) {
                return $row->type;
            })
            ->make(true);
    }

    public function createPipelines(Request $request)
    {
        $name = $request->name;

        try {
            if (!CRMPipeline::where('name', $name)->exists()) {
                $request->validate([
                    'name' => 'string|required',
                    'description' => 'string|required',
                    'percentage_deals' => 'string|required',
                    'type' => 'string|required',
                    'is_active' => 'in:1,0'
                ], [
                    'name.required' => 'Nama pipeline wajib diisi.',
                    'name.string' => 'Nama pipeline harus berupa teks.',
                    'description.required' => 'Deskripsi wajib diisi.',
                    'description.string' => 'Deskripsi harus berupa teks.',
                    'percentage_deals.required' => 'Persentase deal wajib diisi.',
                    'percentage_deals.string' => 'Persentase deal harus berupa teks.',
                    'type.required' => 'Tipe wajib diisi.',
                    'type.string' => 'Tipe harus berupa teks.',
                    'is_active.in' => 'Status tidak valid.',
                ]);

                $lead = CRMPipeline::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'percentage_deals' => $request->percentage_deals,
                    'type' => $request->type,
                    'is_active' => $request->is_active ?? 0,
                ]);

                response()->json(
                    [
                        'success' => true,
                        'message' => 'CRM Pipeline berhasil dibuat',
                        'data' => $lead,
                    ],
                    200,
                );
            } else {
                response()->json(
                    [
                        'success' => true,
                        'message' => 'data CRM pipeline sudah ada',
                    ],
                    400,
                );
            }
        } catch (Exception $err) {
            response()->json(
                [
                    'success' => false,
                    'message' => $err,
                ],
                500,
            );
        }
    }

    public function getAllPipelines(Request $request)
    {
        $leads_name = $request->leads;

        $leads_pipeline_data = CRMLeads::with(['crm_pipelines.crm_prospects'])->where('name', 'like', '%' . $leads_name . '%')
            ->first();

        try {
            if ($leads_pipeline_data) {
                $leads_pipeline_data->with(['crm_prospects']);

                return response()->json(
                    [
                        'success' => true,
                        'data' => $leads_pipeline_data,
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'data' => 'err on load pipeline data',
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
}
