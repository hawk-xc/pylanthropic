<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TokenConfig;
use App\Models\TokenConfigLogs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TokenConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.token-config.index');
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
        $request->validate(['token' => 'required|string']);

        $tokenConfig = TokenConfig::findOrFail($id);
        $oldToken = $tokenConfig->token;
        $newToken = $request->token;

        if ($oldToken !== $newToken) {
            $tokenConfig->token = $newToken;
            $tokenConfig->save();

            TokenConfigLogs::create([
                'token_config_id' => $tokenConfig->id,
                'description' => 'perubahan dari ' . $oldToken . ' ke ' . $newToken,
                'token' => $newToken,
                'created_by' => Auth::id(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Token updated successfully.']);
    }

    public function tokenDatatables(Request $request)
    {
        $data = TokenConfig::query();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('token', function ($row) {
                $token = e($row->token);
                return '<div class="input-group token-container" data-id="' .
                    $row->id .
                    '" data-token="' .
                    $token .
                    '">
                            <input type="password" class="form-control short-url-input token-text" value="' .
                    $token .
                    '" readonly>
                            <button class="btn btn-outline-secondary toggle-vis-btn" type="button"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-outline-secondary copy-token-btn" type="button" data-token="' .
                    $token .
                    '"><i class="fa fa-copy"></i></button>
                            <button class="btn btn-outline-primary inline-edit-btn" type="button"><i class="fa fa-pencil-alt"></i></button>
                        </div>';
            })
            ->rawColumns(['token'])
            ->make(true);
    }

}
