<?php

namespace App\Http\Controllers\Admin;

use Exception;
use DataTables;
use App\Models\LeadsCRM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LeadsCRMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.leads-crm.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program' => 'required|numeric',
            'donatur' => 'required|numeric',
            'description' => 'string|nullable'
        ], [
            'program.required' => 'Program wajib diisi.',
            'program.numeric'  => 'Program harus berupa angka.',
            'donatur.required' => 'Donatur wajib diisi.',
            'donatur.numeric'  => 'Donatur harus berupa angka.',
            'description.string' => 'Deskripsi harus berupa teks.'
        ]);        

        try {
            $lead = new LeadsCRM;
            $lead->program_id = $request->program;
            $lead->donatur_id = $request->donatur;
            $lead->description = $request->description;

            if ($lead->save()) {
                return back()->with('success','success');
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
        }
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function listAllLeads(Request $request)
    {
        $query = LeadsCRM::with(['donatur', 'program']);
    
        return Datatables::of($query)
            ->addColumn('donatur_data', function($lead) {
                return [
                    'id' => $lead->donatur->id,
                    'name' => $lead->donatur->name,
                    'telp' => $lead->donatur->email,
                ];
            })
            ->addColumn('program_data', function($lead) {
                return [
                    'id' => $lead->program->id,
                    'title' => $lead->program->title,
                    'slug' => $lead->program->slug,
                    'short_desc' => $lead->program->short_desc,
                    'thumbnail' => $lead->program->thumbnail
                ];
            })
            ->make(true);
    }

    public function updateLeads(Request $request, $id)
    {

        try {
            $request->validate([
                'new_stage' => 'required|string',
            ]);

            $lead = LeadsCRM::findOrFail($id);

            $newStack = LeadsCRM::where('lead_stage', $request->new_stage)->count();

            $lead->update([
                'lead_stage' => $request->new_stage,
                'lead_stack' => $newStack,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lead stage berhasil diupdate',
                'data' => $lead
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate lead: ' . $e->getMessage()
            ], 500);
        }
    }
}
