<?php

namespace App\Http\Controllers\Admin\Pipeline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CRMLeads;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class CRMLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = CRMLeads::all();

        return view('admin.crm-leads.index', [
            'leads' => $leads
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:crm_leads,name',
            'description' => 'string|nullable'
        ], [
            'name.required' => 'Nama harus diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ]);

        $lastest = CRMLeads::orderBy('sort_number', 'desc')->first();
        $request->merge(['sort_number' => $lastest ? $lastest->sort_number + 1 : 1]);

        try {
            $crm_leads = new CRMLeads;
            $crm_leads->name = $request->name;
            $crm_leads->description = $request->description;
            $crm_leads->sort_number = $request->sort_number;
            $crm_leads->created_by = auth()->user()->id;
            $crm_leads->save();

            return back()->with('message', [
                'type' => 'success',
                'text' => 'Berhasil menambah data Pipeline!',
            ]);
        } catch(Exception $err) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $err->getMessage()
            ]);
        }
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
        $query = CRMLeads::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return Datatables::of($query)
            ->make(true);
    }

    /**
     * Select2 Donatur
     */
    public function select2(Request $request)
    {
        $data = CRMLeads::query()->select('id', 'name');
        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('page')) {
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $data->get(),
            'extra_data' => [
                'last_page' => $last_page,
            ],
        ]);
    }
}
