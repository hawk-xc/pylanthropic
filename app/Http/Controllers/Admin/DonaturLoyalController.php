<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonaturLoyal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DonaturLoyalController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $donatur_id = $request->donatur_id;

        $request->merge([
            'amount' => (int) str_replace('.', '', $request->amount)
        ]);

        $request->validate([
            'program' => 'required|numeric|exists:program,id',
            'payment_type' => 'required|numeric|exists:payment_type,id',
            'donasi_periode' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            // daily
            'daily_time' => Rule::requiredIf($request->donasi_periode === 'daily'),
            // weekly
            'weekly_day' => Rule::requiredIf($request->donasi_periode === 'weekly'),
            'weekly_time' => Rule::requiredIf($request->donasi_periode === 'weekly'),
            // monthly
            'monthly_date' => Rule::requiredIf($request->donasi_periode === 'monthly'),
            // year
            'yearly_month' => Rule::requiredIf($request->donasi_periode === 'yearly'),
            'amount' => 'required|numeric',
            'donasi_description' => 'nullable|string',
            'status_donasi_tetap' => 'nullable'
        ], [
            'program.required' => 'Program wajib diisi.',
            'program.numeric' => 'Program harus berupa angka.',
            'payment_type.required' => 'Tipe pembayaran wajib diisi.',
            'payment_type.numeric' => 'Tipe pembayaran harus berupa angka.',
            'donasi_periode.required' => 'Periode donasi wajib diisi.',
            'donasi_periode.in' => 'Periode donasi tidak valid.',
            'daily_time.required' => 'Waktu donasi harian wajib diisi.',
            'weekly_day.required' => 'Hari donasi mingguan wajib diisi.',
            'weekly_time.required' => 'Waktu donasi mingguan wajib diisi.',
            'monthly_date.required' => 'Tanggal donasi bulanan wajib diisi.',
            'yearly_month.required' => 'Bulan donasi tahunan wajib diisi.',
            'amount.numeric' => 'Jumlah donasi harus berupa angka.',
            'donasi_description.string' => 'Deskripsi donasi harus berupa teks.',
        ]);

        try {
            $data = new DonaturLoyal;
            $data->donatur_id = $donatur_id;
            $data->program_id = $request->program;
            $data->every_period = $request->donasi_periode;

            switch($request->donasi_periode) {
                case 'daily':
                    $data->every_time = $request->daily_time;
                    break;
                case 'weekly':
                    $data->every_time = $request->weekly_time;
                    $data->every_day = $request->weekly_day;
                    break;
                case 'monthly':
                    $data->every_date_period = $request->monthly_date;
                    break;
                case 'yearly':
                    $data->every_month_period = $request->yearly_month;
                    break;
            }

            $data->nominal = $request->amount;
            $data->payment_type_id = $request->payment_type;
            $data->desc = $request->donasi_description ?? null;
            $data->is_active = $request->status_donasi_tetap ?? null;
            $data->created_by = auth()->user()->id; // loged in user;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Data donatur loyal berhasil disimpan.',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $donaturLoyal = DonaturLoyal::with(['program', 'payment_type'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $donaturLoyal
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donatur_loyal_id = $request->donatur_loyal_id;

        $request->merge([
            'amount' => (int) str_replace('.', '', $request->amount)
        ]);

        $request->validate([
            'program' => 'required|numeric|exists:program,id',
            'payment_type' => 'required|numeric|exists:payment_type,id',
            'donasi_periode' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            // daily
            'daily_time' => Rule::requiredIf($request->donasi_periode === 'daily'),
            // weekly
            'weekly_day' => Rule::requiredIf($request->donasi_periode === 'weekly'),
            'weekly_time' => Rule::requiredIf($request->donasi_periode === 'weekly'),
            // monthly
            'monthly_date' => Rule::requiredIf($request->donasi_periode === 'monthly'),
            // year
            'yearly_month' => Rule::requiredIf($request->donasi_periode === 'yearly'),
            'amount' => 'required|numeric',
            'donasi_description' => 'nullable|string',
            'edit_status_donasi_tetap' => 'nullable'
        ], [
            'program.required' => 'Program wajib diisi.',
            'program.numeric' => 'Program harus berupa angka.',
            'payment_type.required' => 'Tipe pembayaran wajib diisi.',
            'payment_type.numeric' => 'Tipe pembayaran harus berupa angka.',
            'donasi_periode.required' => 'Periode donasi wajib diisi.',
            'donasi_periode.in' => 'Periode donasi tidak valid.',
            'daily_time.required' => 'Waktu donasi harian wajib diisi.',
            'weekly_day.required' => 'Hari donasi mingguan wajib diisi.',
            'weekly_time.required' => 'Waktu donasi mingguan wajib diisi.',
            'monthly_date.required' => 'Tanggal donasi bulanan wajib diisi.',
            'yearly_month.required' => 'Bulan donasi tahunan wajib diisi.',
            'amount.numeric' => 'Jumlah donasi harus berupa angka.',
            'donasi_description.string' => 'Deskripsi donasi harus berupa teks.',
        ]);

        try {
            $data = DonaturLoyal::findOrFail($donatur_loyal_id);

            $data->every_period = $request->donasi_periode;

            switch($request->donasi_periode) {
                case 'daily':
                    $data->every_time = $request->daily_time;
                    break;
                case 'weekly':
                    $data->every_time = $request->weekly_time;
                    $data->every_day = $request->weekly_day;
                    break;
                case 'monthly':
                    $data->every_date_period = $request->monthly_date;
                    break;
                case 'yearly':
                    $data->every_month_period = $request->yearly_month;
                    break;
            }

            $data->program_id = $request->program;
            $data->nominal = $request->amount;
            $data->desc = $request->donasi_description ?? null;
            $data->payment_type_id = $request->payment_type;
            $data->is_active = $request->edit_status_donasi_tetap ?? null;
            $data->created_by = auth()->user()->id;
            $data->save();

            return redirect()->back()->with('success', 'Data donatur loyal berhasil diupdate.');
        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Data donatur loyal berhasil diupdate.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = DonaturLoyal::findOrFail($id);

        try {
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data donatur tetap berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function datatablesDonaturLoyal(Request $request)
    {
        try {
            // First get the donatur to ensure it exists
            $donatur = \App\Models\Donatur::findOrFail($request->donatur_id);

            // Then query the loyal donations through the relationship
            $query = $donatur->donaturLoyal()->with(['program']); // Removed paymentType since it's not defined

            // Get total records count
            $count_total = $query->count();

            // Handle search
            if ($request->has('search') && $request->search['value']) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->whereHas('program', function($q) use ($search) {
                            $q->where('title', 'like', "%{$search}%");
                        })
                        ->orWhere('nominal', 'like', "%{$search}%")
                        ->orWhere('desc', 'like', "%{$search}%");
                });
            }

            // Get filtered count
            $count_filter = $query->count();

            // Apply ordering
            if ($request->has('order')) {
                $columns = [
                    0 => 'id',
                    1 => 'program.title',
                    2 => 'nominal',
                    3 => 'every_period',
                    4 => 'is_active',
                    5 => 'created_at',
                ];

                $orderColumn = $columns[$request->order[0]['column']] ?? 'id';
                $orderDirection = $request->order[0]['dir'] ?? 'desc';

                if ($orderColumn === 'program.title') {
                    $query->join('programs', 'programs.id', '=', 'donatur_loyal.program_id')
                        ->orderBy('programs.title', $orderDirection);
                } else {
                    $query->orderBy($orderColumn, $orderDirection);
                }
            }

            // Apply pagination
            $data = $query->skip($request->start ?? 0)->take($request->length ?? 10)->get();

            function determineMonthName($month_id) {
                        $bulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];

                        return $bulan[$month_id] ?? '-';
                    }

            return Datatables::of($data)
                ->with([
                    "recordsTotal" => $count_total,
                    "recordsFiltered" => $count_filter,
                ])
                ->addColumn('program', function($row) {
                    if ($row->program) {
                        return '<a href="'.route('program.index', $row->program->slug).'" target="_blank">
                                    '.$row->program->title.'
                                </a>';
                    }
                    return '-';
                })
                ->addColumn('schedule', function($row) {
                    switch($row->every_period) {
                        case 'daily':
                            $schedule = 'Setiap hari pukul '.($row->every_time ? date('H:i', strtotime($row->every_time)) : '-');
                            break;
                        case 'weekly':
                            $schedule = 'Setiap minggu pada '.($row->every_day ?? '-') . ' pukul ' . ($row->every_time ? date('H:i', strtotime($row->every_time)) : '-');
                            break;
                        case 'monthly':
                            $schedule = 'Setiap bulan tanggal '.($row->every_date_period ?? '-');
                            break;
                        case 'yearly':
                            $schedule = 'Setiap tahun bulan '.determineMonthName($row->every_month_period) ?? '-';
                            break;
                        default:
                            $schedule = 'Custom: '.($row->every_moment ?? '-');
                    }

                    return $schedule;
                })
                ->addColumn('nominal', function($row) {
                    return 'Rp '.number_format($row->nominal);
                })
                ->addColumn('status', function($row) {
                    if ($row->is_active) {
                        return '<span class="badge badge-success">Aktif</span>';
                    }
                    return '<span class="badge badge-danger">Nonaktif</span>';
                })
                ->addColumn('payment', function($row) {
                    return $row->payment_type_id ? $row->payment_type->name : 'Belum dipilih';
                })
                ->addColumn('action', function($row) {
                    $actionBtn = '
                        <button onclick="openEditDonaturLoyalModal('.$row->id.')" class="edit btn btn-warning btn-xs mb-1" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="delete btn btn-danger btn-xs mb-1" data-id="'.$row->id.'" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>';

                    return $actionBtn;
                })
                ->addColumn('created_at', function($row) {
                    return $row->created_at->format('d M Y H:i');
                })
                ->rawColumns(['program', 'status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
