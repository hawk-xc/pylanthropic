<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Select2 User
     */
    public function select2(Request $request)
    {
        $data = User::query()->select('id', 'name');
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
