<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\TrackingVisitor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use DataTables;
use Exception;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.program.create');
    }

    public function storeImagecontent(Request $request)
    {
        $number = $request->number;
        $number = str_replace('img', '', $number);

        $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->name));
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

        $file = $request->file('file');
        $filename = $filename . '_' . $number . '.jpg';

        $image = Image::make($file->getRealPath())
            ->fit(580, 780)
            ->encode('jpg', 80);

        Storage::disk('public_uploads')->put(
            'images/program/content/' . $filename,
            $image->stream()        
        );

        // Generate URL
        $link_img = url('images/program/content/' . $filename);

        return [
            'link' => $link_img,
            'full' => '<img data-original="' . $link_img . '" class="lazyload" alt="' . ucwords($request->name) . ' - Bantubersama.com" />',
        ];
    }

    public function uploadImageContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => $validator->errors()->first(),
                ],
            ], 400);
        }

        try {
            $file = $request->file('file');
            $programTitle = $request->input('program_title');

            $baseName = str_replace([' ', '-', '&', ':'], '_', trim($programTitle));
            $baseName = preg_replace('/[^A-Za-z0-9\_]/', '', $baseName);

            // Path lengkap ke direktori penyimpanan (sesuai dengan store)
            $contentDir = base_path('../public_html/images/program/content');

            // Pastikan direktori ada
            if (!File::exists($contentDir)) {
                File::makeDirectory($contentDir, 0755, true);
            }

            // Cari file yang sudah ada dengan pola nama yang sama
            $pattern = $contentDir . '/' . $baseName . '_*.jpg';
            $existingFiles = glob($pattern);

            // Hitung counter berikutnya
            $counter = 1;
            if (!empty($existingFiles)) {
                natsort($existingFiles);
                $lastFile = end($existingFiles);
                preg_match('/_(\d+)\.jpg$/', $lastFile, $matches);
                if (isset($matches[1])) {
                    $counter = (int) $matches[1] + 1;
                }
            }

            // Generate nama file baru
            $filename = $baseName . '_' . $counter . '.jpg';

            // Proses gambar dengan Intervention Image (sesuai dengan store)
            $image = Image::make($file->getRealPath())
                ->fit(580, 780) // Ukuran untuk content image
                ->encode('jpg', 80); // Format dan kualitas sesuai store

            // Simpan gambar (sesuai dengan store)
            $image->save($contentDir . '/' . $filename);

            // Generate URL yang konsisten
            $url = url('images/program/content/' . $filename);

            return response()->json([
                'location' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ], 500);
        }
    }

    /**
     * Store image content
     */
    public function checkUrl(Request $request)
    {
        $url = $request->url;
        $url = str_replace([' ', "'", '"', ',', ';', ':', '&'], '', $url);
        $url = preg_replace('/[^A-Za-z0-9\_-]/', '', $url);

        $cek = Program::where('slug', $url)
            ->where('is_publish', 1)
            ->where('end_date', '>', date('Y-m-d') . ' 23:59:59')
            ->select('id')
            ->count();

        if ($cek < 1) {
            return 'valid';
        } else {
            return 'invalid';
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $forever_checked = $request->has('forever_checked') == 'on';

        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'category' => 'required',
            'organization' => 'required|numeric',
            'nominal' => 'required',
            'show' => 'required',
            'img_primary' => 'required|file|max:1024',
            'caption' => 'required',
            'story' => 'required',
        ]);

        if ($forever_checked) {
            $request->validate([
                'date_end' => 'nullable',
            ]);
        } else {
            $request->validate([
                'date_end' => 'required|date',
            ]);
        }

        if (!$request->has('same_as_thumbnail')) {
            $request->validate([
                'thumbnail' => 'required|file|max:600',
            ]);
        }

        try {
            $data = new Program();
            $data->title = $request->title;
            $data->slug = urlencode($request->url);
            $data->organization_id = $request->organization;
            $data->nominal_request = str_replace('.', '', $request->nominal);
            $data->nominal_approved = str_replace('.', '', $request->nominal);
            $data->end_date = $request->date_end ?? null;
            $data->short_desc = $request->caption;
            $data->is_islami = $request->is_islami ? 1 : 0;

            $story = str_replace('&lt;', '<', $request->story);
            $story = str_replace('&gt;', '>', $story);
            $story = str_replace('Bantubersama.com" /></p>', 'Bantubersama.com" />', $story);
            $story = str_replace('<p><img', '<img', $story);
            $data->about = $story . '<img class="lazyload" data-original="https://bantubersama.com/public/images/program/cara_donasi.webp" alt="Cara Berdonasi di Bantubersama.com" />';

            if ($request->show == 1) {
                $data->is_publish = 1;
                $data->is_recommended = 0;
                $data->is_show_home = 0;
            } elseif ($request->show == 2) {
                $data->is_publish = 1;
                $data->is_recommended = 1;
                $data->is_show_home = 0;
            } elseif ($request->show == 3) {
                $data->is_publish = 1;
                $data->is_recommended = 0;
                $data->is_show_home = 1;
            } elseif ($request->show == 4) {
                $data->is_publish = 0;
                $data->is_recommended = 0;
                $data->is_show_home = 0;
            }

            $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->title));
            $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            $programDir = base_path('../public_html/images/program');
            $thumbnailDir = $programDir . '/thumbnail';

            if (!File::exists($programDir)) {
                File::makeDirectory($programDir, 0755, true);
            }
            if (!File::exists($thumbnailDir)) {
                File::makeDirectory($thumbnailDir, 0755, true);
            }

            // upload image primary (Landing Page size)
            $filei = $request->file('img_primary');
            $image = Image::make($filei->getRealPath());

            // Resize SOP Landing Page (600x320)
            $image->resize(600, 320, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->encode('jpg', 80);
            $imageName = $filename . '.jpg';
            $image->save($programDir . '/' . $imageName);
            $data->image = $imageName;

            if ($request->has('same_as_thumbnail')) {
                $thumbnail = Image::make($filei->getRealPath());

                // Resize SOP untuk Thumbnail (292x156)
                $thumbnail->resize(292, 156, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbnail->encode('jpg', 60);
                $thumbnailName = 'thumbnail_' . $filename . '.jpg';
                $thumbnail->save($thumbnailDir . '/' . $thumbnailName);
                $data->thumbnail = $thumbnailName;
                $data->same_as_thumbnail = true;
            } else {
                $filet = $request->file('thumbnail');
                $thumbnail = Image::make($filet->getRealPath());

                // Resize SOP Thumbnail (292x156)
                $thumbnail->resize(292, 156, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbnail->encode('jpg', 60);
                $thumbnailName = 'thumbnail_' . $filename . '.jpg';
                $thumbnail->save($thumbnailDir . '/' . $thumbnailName);
                $data->thumbnail = $thumbnailName;
                $data->same_as_thumbnail = false;
            }

            $data->approved_at = date('Y-m-d H:i:s');
            $data->approved_by = 1;
            $data->created_by = 1;
            $data->save();
            $program_id = $data->id;

            // insert program categories
            if (count($request->category) > 1) {
                foreach ($request->category as $category) {
                    $data_categories = new \App\Models\ProgramCategories();
                    $data_categories->program_id = $program_id;
                    $data_categories->program_category_id = $category;
                    $data_categories->is_active = 1;
                    $data_categories->save();
                }
            } else {
                $data_categories = new \App\Models\ProgramCategories();
                $data_categories->program_id = $program_id;
                $data_categories->program_category_id = $request->category[0];
                $data_categories->is_active = 1;
                $data_categories->save();
            }

            return redirect(route('adm.program.index'))->with('success', 'Berhasil menambahkan program baru');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update, ada kesalahan teknis: ' . $e->getMessage());
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
        $program = Program::select('program.*', 'organization.name')->join('organization', 'organization.id', 'organization_id')->where('program.id', $id)->first();

        return view('admin.program.edit', compact('program'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string',
                'url' => 'required|string',
                'organization' => 'required|numeric',
                'nominal' => 'required',
                'date_end' => 'required|date',
                'show' => 'required',
                'caption' => 'required',
                'img_primary' => 'file|max:800',
                'thumbnail' => 'file|max:400',
            ],
            [
                'required' => 'Kolom :attribute wajib diisi.',
                'string' => 'Kolom :attribute harus berupa teks.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
                'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            ],
        );

        try {
            $data = Program::findOrFail($id);
            $data->title = $request->title;
            $data->slug = urlencode($request->url);
            $data->organization_id = $request->organization;
            $data->nominal_request = str_replace('.', '', $request->nominal);
            $data->nominal_approved = str_replace('.', '', $request->nominal);
            $data->end_date = $request->date_end;
            $data->short_desc = $request->caption;
            $data->is_islami = $request->is_islami ? 1 : 0;
            $data->optimation_fee = $request->optimation_fee;
            $data->show_minus = $request->show_minus;
            $data->same_as_thumbnail = $request->has('same_as_thumbnail');
            $data->about = $request->story;

            // Handle show options
            switch ($request->show) {
                case 1:  // Pencarian saja
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 2:  // Tampil di Pilihan
                    $data->is_publish = 1;
                    $data->is_recommended = 1;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 3:  // Tampil di Terbaru
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 1;
                    $data->is_urgent = 0;
                    break;
                case 4:  // Sembunyikan
                    $data->is_publish = 0;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 0;
                    break;
                case 5:  // Mendesak
                    $data->is_publish = 1;
                    $data->is_recommended = 0;
                    $data->is_show_home = 0;
                    $data->is_urgent = 1;
                    break;
            }

            $filename = str_replace([' ', '-', '&', ':'], '_', trim($request->title));
            $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename);

            $programDir = base_path('../public_html/images/program');
            $thumbnailDir = $programDir . '/thumbnail';

            // Handle primary image if it exists
            if ($request->hasFile('img_primary')) {
                $filei = $request->file('img_primary');
                $image = Image::make($filei->getRealPath());

                // Resize SOP Landing Page (600x320)
                $image->resize(600, 320, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $image->encode('jpg', 80);
                $imageName = $filename . '.jpg';
                $image->save($programDir . '/' . $imageName);
                $data->image = $imageName;
            }

            if ($request->has('same_as_thumbnail')) {
                // If a new primary image was uploaded, use it.
                if ($request->hasFile('img_primary')) {
                    $filei = $request->file('img_primary');
                    $thumbnail = Image::make($filei->getRealPath());

                    // Resize SOP untuk Thumbnail (292x156)
                    $thumbnail->resize(292, 156, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $thumbnail->encode('jpg', 60);
                    $thumbnailName = 'thumbnail_' . $filename . '.jpg';
                    $thumbnail->save($thumbnailDir . '/' . $thumbnailName);
                    $data->thumbnail = $thumbnailName;
                } else if ($data->image) {
                    $existingImagePath = public_path('images/program/' . $data->image);
                    if (file_exists($existingImagePath)) {
                        $thumbnail = Image::make($existingImagePath);

                        // Resize SOP untuk Thumbnail (292x156)
                        $thumbnail->resize(292, 156, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $thumbnail->encode('jpg', 60);
                        // Use the new filename for the thumbnail to keep it consistent with the title
                        $thumbnailName = 'thumbnail_' . $filename . '.jpg';
                        $thumbnail->save(public_path('images/program/' . $thumbnailName));
                        $data->thumbnail = $thumbnailName;
                    }
                }
                $data->same_as_thumbnail = true;
            } else {
                // upload thumbnail if it exists
                if ($request->hasFile('thumbnail')) {
                    $filet = $request->file('thumbnail');
                    $thumbnail = Image::make($filet->getRealPath());

                    // Resize SOP untuk Thumbnail (292x156)
                    $thumbnail->resize(292, 156, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    $thumbnail->encode('jpg', 60);
                    $thumbnailName = 'thumbnail_' . $filename . '.jpg';
                    $thumbnail->save(public_path('images/program/' . $thumbnailName));
                    $data->thumbnail = $thumbnailName;
                }
                $data->same_as_thumbnail = false;
            }

            $data->updated_by = Auth::user()->id;
            $data->updated_at = now();
            $data->save();

            return redirect(route('adm.program.index'))->with('success', 'Berhasil mengubah program');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Datatables Program
     */
    public function datatablesProgram(Request $request)
    {
        // if ($request->ajax()) {
        $data = Program::select('program.*', 'organization.name as organization')->join('organization', 'organization.id', 'program.organization_id')->orderBy('program.donate_sum', 'DESC');

        if (isset($request->active)) {
            if ($request->active == 1) {
                $data = $data->where('is_publish', 1)->where('end_date', '>=', date('Y-m-d'));
            }
        }

        if (isset($request->inactive)) {
            if ($request->inactive == 1) {
                $data = $data->where('is_publish', 0);
            }
        }

        if (isset($request->winning)) {
            if ($request->winning == 1) {
                $data = $data->where('donate_sum', '>=', 8000000);
            }
        }

        if (isset($request->recom)) {
            if ($request->recom == 1) {
                $data = $data->where('is_recommended', 1);
            }
        }

        if (isset($request->urgent)) {
            if ($request->urgent == 1) {
                $data = $data->where('is_urgent', 1);
            }
        }

        if (isset($request->newest)) {
            if ($request->newest == 1) {
                $data = $data->where('is_show_home', 1);
            }
        }

        if (isset($request->publish15day)) {
            if ($request->publish15day == 1) {
                $data = $data->where('program.approved_at', '>=', date('Y-m-d', strtotime(date('Y-m-d') . '-15 days')));
            }
        }

        if (isset($request->end15day)) {
            if ($request->end15day == 1) {
                $data = $data->where('end_date', '<=', date('Y-m-d', strtotime(date('Y-m-d') . '+15 days')));
            }
        }

        if (isset($request->program_title)) {
            $data = $data->where('program.title', 'like', '%' . $request->program_title . '%');
        }

        if (isset($request->organization_name)) {
            $data = $data->where('organization.name', 'like', '%' . $request->organization_name . '%');
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q
                    ->where('program.title', 'like', '%' . $search . '%')
                    ->orWhere('program.slug', 'like', '%' . $search . '%')
                    ->orWhere('program.short_desc', 'like', '%' . $search . '%')
                    ->orWhere('organization.name', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data->skip($start)->take($pageSize);

        $data = $data->get();

        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('nominal', function ($row) {
                $sum = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->sum('nominal_final');
                if ($sum > 0) {
                    $sum_percent = round(($sum / $row->nominal_approved) * 100, 2);
                } else {
                    $sum_percent = 0;
                }

                $spend = \App\Models\ProgramSpend::where('program_id', $row->id)->where('status', 'done')->sum('nominal_approved');
                if ($spend > 0 && $sum > 0) {
                    $spend_percent = round(($spend / $sum) * 100, 2);
                } else {
                    $spend_percent = 0;
                }

                $param = $row->id . ", '" . ucwords(str_replace("'", '', $row->title)) . "'";

                return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary('
                    . $param
                    . ')">
                        <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.'
                    . str_replace(',', '.', number_format($row->nominal_approved))
                    . '</span>
                        <br>
                        <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate('
                    . $param
                    . ')">
                        <i class="fa fa-money-bill icon-gradient bg-happy-green"></i> Rp.'
                    . number_format($sum)
                    . ' ('
                    . $sum_percent
                    . '%)</span>
                        <br>
                        <span class="badge badge-light" style="cursor:pointer" onclick="inpSpend('
                    . $param
                    . ')">
                        <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.'
                    . number_format($spend)
                    . ' ('
                    . $spend_percent
                    . '%)</span>';
            })
            ->addColumn('status', function ($row) {

                if ($row->approved_at !== null) {
                    // disetujui
                    if ($row->end_date > date('Y-m-d') && $row->is_publish == '1') {
                        // masih publish belum berakhir
                        if ($row->is_recommended == 1) {
                            $status = '<span class="badge badge-success">Tampil Dipilihan</span>';
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : 'selamanya');
                        } elseif ($row->is_show_home == 1) {
                            $status = '<span class="badge badge-success">Tampil Diterbaru</span>';
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : 'selamanya');
                        } else {
                            $status = '<span class="badge badge-success">Tampil Dipencarian</span>';
                            $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                            $status .= '<br>End: ' . ($row->end_date ? date('d-M-Y', strtotime($row->end_date)) : 'selamanya');
                        }
                    } elseif ($row->is_publish == '0') {
                        // tidak dipublikasi
                        $status = '<span class="badge badge-danger">Tidak Tampil</span>';
                        $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                        $status .= '<br>End: ' . $formatEndDate($row->end_date);
                    } else {
                        // sudah berakhir
                        $status = '<span class="badge badge-danger">Sudah Berakhir</span>';
                        $status .= '<br>Start: ' . date('d-M-Y', strtotime($row->approved_at));
                        $status .= '<br>End: ' . $formatEndDate($row->end_date);
                    }
                } else {
                    // belum disetujui
                    $status = '<span class="badge badge-secondary">Belum Disetujui</span>';
                }

                return $status;
            })
            ->addColumn('stats', function ($row) {
                return '-';
            })
            ->addColumn('donate', function ($row) {
                // $count_view      = TrackingVisitor::where('program_id', $row->id)->where('page_view', 'landing_page')->count();
                // $count_form_page = TrackingVisitor::where('program_id', $row->id)->where('page_view', 'form')->count();

                // $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> ".number_format($count_form_page);
                // $checkout = \App\Models\Transaction::where('program_id', $row->id)->count('id');
                // $count    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->count('id');

                // if($count_view>0 && $count_form_page>0) {
                //     $interest_per  = round($count_form_page/$count_view*100, 2);
                // } else {
                //     $interest_per  = 0;
                // }

                // if($checkout>0 && $count_view>0) {
                //     $checkout_per = round($checkout/$count_view*100, 2);
                // } else {
                //     $checkout_per = 0;
                // }

                // if($count>0 && $checkout>0) {
                //     $count_per = round($count/$checkout*100, 2);
                // } else {
                //     $count_per = 0;
                // }

                // return $interest.' ('.$interest_per.'%)
                //     <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '.number_format($checkout).' ('.$checkout_per.'%)
                //     <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '.number_format($count).' ('.$count_per.'%)';

                // return '-';

                // $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> ".number_format($row->count_pra_checkout);
                // $checkout = \App\Models\Transaction::where('program_id', $row->id)->count('id');
                // $count    = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->count('id');

                // if($row->count_view>0 && $row->count_pra_checkout>0) {
                //     $interest_per  = round($row->count_pra_checkout/$row->count_view*100, 2);
                // } else {
                //     $interest_per  = 0;
                // }

                // if($checkout>0 && $row->count_view>0) {
                //     $checkout_per = round($checkout/$row->count_view*100, 2);
                // } else {
                //     $checkout_per = 0;
                // }

                // if($count>0 && $checkout>0) {
                //     $count_per = round($count/$checkout*100, 2);
                // } else {
                //     $count_per = 0;
                // }

                // return $interest.' ('.$interest_per.'%)
                //     <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '.number_format($checkout).' ('.$checkout_per.'%)
                //     <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '.number_format($count).' ('.$count_per.'%)';

                return number_format($row->donate_sum);
            })
            ->addColumn('action', function ($row) {
                $url_edit = route('adm.program.edit', $row->id);
                // $url_edit  = route('adm.report.settlement');
                $actionBtn =
                    '<a href="'
                    . $url_edit
                    . '" class="edit btn btn-warning btn-xs mb-1" title="Edit"><i class="fa fa-edit"></i></a>
                                <a href="'
                    . route('adm.program.detail.stats', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Statistik"><i class="fa fa-chart-line"></i></a>
                                <a href="'
                    . route('adm.program.detail.fundraiser', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Donasi"><i class="fa fa-donate"></i></a>
                                <a href="'
                    . route('adm.program.detail.donatur', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Donatur"><i class="fa fa-users"></i></a>
                                <a href="'
                    . route('adm.program.detail.fundraiser', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Fundraiser"><i class="fa fa-people-carry"></i></a>
                                <a href="'
                    . route('adm.program.detail.fundraiser', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Penyaluran"><i class="fa fa-hand-holding-heart"></i></a>
                                <a href="'
                    . route('adm.program.detail.fundraiser', $row->id)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Operasional"><i class="fa fa-file-invoice-dollar"></i></a>
                                <a href="'
                    . route('program.index', $row->slug)
                    . '" class="edit btn btn-info btn-xs mb-1" title="Link" target="_blank"><i class="fa fa-external-link-alt"></i></a>
                                ';
                return $actionBtn;
            })
            ->rawColumns(['action', 'nominal', 'status', 'stats', 'donate'])
            ->make(true);
        // }
    }

    /**
     * Datatables Program Dashboard
     */
    public function datatablesProgramDashboard(Request $request)
    {
        // if ($request->ajax()) {
        $data = Program::select('program.*', 'organization.name as organization')->join('organization', 'organization.id', 'program.organization_id')->orderBy('program.donate_sum', 'DESC');

        $data = $data->where(function ($q) use ($data) {
            $q->where('is_recommended', 1)->orWhere('is_show_home', 1)->orWhere('is_urgent', 1);
        });

        if (isset($request->is_publish)) {
            $data = $data->where('is_publish', $request->is_publish)->where('end_date', '>', date('Y-m-d H:i:s'));
        }

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q
                    ->where('program.title', 'like', '%' . $search . '%')
                    ->orWhere('program.slug', 'like', '%' . $search . '%')
                    ->orWhere('program.short_desc', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data->skip($start)->take($pageSize);

        $data = $data->get();

        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('nominal', function ($row) {
                $sum = \App\Models\Transaction::where('program_id', $row->id)->where('status', 'success')->sum('nominal_final');
                if ($sum > 0) {
                    $sum_percent = round(($sum / $row->nominal_approved) * 100, 2);
                } else {
                    $sum_percent = 0;
                }

                $spend = \App\Models\ProgramSpend::where('program_id', $row->id)->where('status', 'done')->sum('nominal_approved');
                if ($spend > 0 && $sum > 0) {
                    $spend_percent = round(($spend / $sum) * 100, 2);
                } else {
                    $spend_percent = 0;
                }

                $param = $row->id . ", '" . ucwords(str_replace("'", '', $row->title)) . "'";

                return '<span class="badge badge-light" style="cursor:pointer" onclick="showSummary('
                    . $param
                    . ')">
                        <i class="fa fa-check-double icon-gradient bg-happy-green"></i> Rp.'
                    . str_replace(',', '.', number_format($row->nominal_approved))
                    . '</span>
                        <br>
                        <span class="badge badge-light modal_status" style="cursor:pointer" onclick="showDonate('
                    . $param
                    . ')">
                        <i class="fa fa-money-bill icon-gradient bg-happy-green"></i> Rp.'
                    . number_format($sum)
                    . ' ('
                    . $sum_percent
                    . '%)</span>
                        <br>
                        <span class="badge badge-light" style="cursor:pointer" onclick="inpSpend('
                    . $param
                    . ')">
                        <i class="fa fa-credit-card icon-gradient bg-strong-bliss"></i> Rp.'
                    . number_format($spend)
                    . ' ('
                    . $spend_percent
                    . '%)</span>';
            })
            ->addColumn('ads', function ($row) {
                $trans_prime1 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 00:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 09:59:59');
                if ($trans_prime1->count() > 9) {
                    $prime1_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 4) {
                    $prime1_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 2) {
                    $prime1_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime1->count() > 0) {
                    $prime1_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                } else {
                    $prime1_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime1->count()) . ' | Rp.' . number_format($trans_prime1->sum('nominal_final')) . '</span>';
                }

                $trans_prime2 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 10:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 14:59:59');
                if ($trans_prime2->count() > 9) {
                    $prime2_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 4) {
                    $prime2_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 2) {
                    $prime2_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime2->count() > 0) {
                    $prime2_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                } else {
                    $prime2_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime2->count()) . ' | Rp.' . number_format($trans_prime2->sum('nominal_final')) . '</span>';
                }

                $trans_prime3 = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', '>', date('Y-m-d') . ' 15:00:00')
                    ->where('created_at', '<', date('Y-m-d') . ' 23:59:59');
                if ($trans_prime3->count() > 9) {
                    $prime3_ads = '<span class="badge badge-success" title="10 Donasi Keatas">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 4) {
                    $prime3_ads = '<span class="badge badge-primary" title="5-9 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 2) {
                    $prime3_ads = '<span class="badge badge-info" title="3-4 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } elseif ($trans_prime3->count() > 0) {
                    $prime3_ads = '<span class="badge badge-secondary" title="0-2 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                } else {
                    $prime3_ads = '<span class="badge badge-danger" title="0 Donasi">' . number_format($trans_prime3->count()) . ' | Rp.' . number_format($trans_prime3->sum('nominal_final')) . '</span>';
                }

                $prime1 = 'Jam 00:00 - 10:00 = ' . $prime1_ads;
                $prime2 = 'Jam 10:00 - 15:00 = ' . $prime2_ads;
                $prime3 = 'Jam 15:00 - 24:00 = ' . $prime3_ads;
                return $prime1 . '<br>' . $prime2 . '<br>' . $prime3;
            })
            ->addColumn('stats', function ($row) {
                $count_view = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_amount_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_payment_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'payment_type')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();

                $view = "<i class='fa fa-eye icon-gradient bg-malibu-beach'></i> " . number_format($count_view);
                $amount_page = "<i class='fa fa-money-bill icon-gradient bg-malibu-beach'></i> " . number_format($count_amount_page);
                $payment_page = "<i class='fa fa-credit-card icon-gradient bg-malibu-beach'></i> " . number_format($count_payment_page);

                if ($count_view > 0 && $count_amount_page > 0) {
                    $amount_per = round(($count_amount_page / $count_view) * 100, 2);
                } else {
                    $amount_per = 0;
                }

                if ($count_view > 0 && $count_payment_page > 0) {
                    $count_payment_page_per = round(($count_payment_page / $count_view) * 100, 2);
                } else {
                    $count_payment_page_per = 0;
                }

                return $view . '<br>' . $amount_page . ' (' . $amount_per . '%) <br>' . $payment_page . ' (' . $count_payment_page_per . '%)';
            })
            ->addColumn('donate', function ($row) {
                $count_view = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();
                $count_form_page = TrackingVisitor::where('program_id', $row->id)
                    ->where('page_view', 'form')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count();

                $interest = "<i class='fa fa-file icon-gradient bg-malibu-beach'></i> " . number_format($count_form_page);
                $checkout = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count('id');
                $count = \App\Models\Transaction::where('program_id', $row->id)
                    ->where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d') . '%')
                    ->count('id');

                if ($count_view > 0 && $count_form_page > 0) {
                    $interest_per = round(($count_form_page / $count_view) * 100, 2);
                } else {
                    $interest_per = 0;
                }

                if ($checkout > 0 && $count_view > 0) {
                    $checkout_per = round(($checkout / $count_view) * 100, 2);
                } else {
                    $checkout_per = 0;
                }

                if ($count > 0 && $checkout > 0) {
                    $count_per = round(($count / $checkout) * 100, 2);
                } else {
                    $count_per = 0;
                }

                return $interest
                    . ' ('
                    . $interest_per
                    . '%)
                        <br> <i class="fa fa-shopping-cart icon-gradient bg-malibu-beach"></i> '
                    . number_format($checkout)
                    . ' ('
                    . $checkout_per
                    . '%)
                        <br> <i class="fa fa-heart icon-gradient bg-happy-green"></i> '
                    . number_format($count)
                    . ' ('
                    . $count_per
                    . '%)';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-warning btn-xs">Edit</a>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'nominal', 'ads', 'stats', 'donate'])
            ->make(true);
        // }
    }

    /**
     * Get stats of visitor
     */
    public function statsVisitor(Request $request)
    {
        if ($request->type == 'landing_page') {
            return $this->analyticVisitorShow();
        } elseif ($request->type == 'amount') {
            return $this->analyticVisitorClickDonateShow();
        } elseif ($request->type == 'donate') {
            return $this->analyticVisitorDonateShow();
        } elseif ($request->type == 'paid') {
            return $this->analyticVisitorPaidShow();
        }
    }

    /**
     * Show analytic Visitor
     */
    public function analyticVisitorShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('program_id', $id)
                    ->where('page_view', 'landing_page')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor click donate button
     */
    public function analyticVisitorClickDonateShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = TrackingVisitor::where('program_id', $id)
                    ->where('page_view', 'amount')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor donate/transaction
     */
    public function analyticVisitorDonateShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('program_id', $id)
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show analytic Visitor donate/transaction
     */
    public function analyticVisitorPaidShow($id = '', $row = 30)
    {
        $dn = date('Y-m-d');

        if ($id == '') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
        } else {
            // if($request->per=='day') {
            for ($i = 0; $i < $row; $i++) {
                $data[] = Transaction::where('program_id', $id)
                    ->where('status', 'success')
                    ->where('created_at', 'like', date('Y-m-d', strtotime($dn . '-' . $i . ' day')) . '%')
                    ->count();
            }
            // } elseif($request->per=='week') {
            //     $data[] = '-';
            // } elseif($request->per=='month') {
            //     $data[] = '-';
            // } else {
            //     $data[] = '-';
            // }
        }

        return $data;
    }

    /**
     * Show Donate from datatable program
     */
    public function showDonate(Request $request)
    {
        // $program = Program::where('id', $id)->first();
    }

    /**
     * Show detail statistic Program
     */
    public function detailStats(Request $request)
    {
        $id = $request->id;
        $dn = date('Y-m-d');
        $program_name = Program::where('id', $id)->select('title')->first()->title;
        $avg_all = Transaction::where('program_id', $id)->avg('nominal_final');

        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'landing_page')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'amount')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'payment_type')
            ->count();
        $visitor_today[] = TrackingVisitor::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('page_view', 'form')
            ->count();
        $visitor_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->count();
        $visitor_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', 'success')
            ->count();

        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', 'success')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', '<>', 'success')
            ->count();
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->where('status', '<>', 'success')
            ->sum('nominal_final');
        $donate_today[] = Transaction::where('program_id', $id)
            ->where('created_at', 'like', $dn . '%')
            ->avg('nominal_final');
        $donate_today[] = $avg_all;

        $summary[] = Transaction::where('program_id', $id)->where('status', 'success')->sum('nominal_final');
        $summary[] = Transaction::where('program_id', $id)->where('status', 'success')->count();
        $summary[] = \App\Models\Payout::where('program_id', $id)->where('status', 'paid')->sum('nominal_approved');
        $summary[] = \App\Models\Payout::where('program_id', $id)->where('status', 'paid')->count();
        $summary[] = \App\Models\ProgramSpend::where('program_id', $id)->where('status', 'done')->where('type', 'ads')->sum('nominal_approved');
        $summary[] = \App\Models\ProgramSpend::where('program_id', $id)->where('status', 'done')->where('type', 'ads')->count();

        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'landing_page')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'amount')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'payment_type')->count();
        $visitor_all[] = TrackingVisitor::where('program_id', $id)->where('page_view', 'form')->count();
        $visitor_all[] = Transaction::where('program_id', $id)->count();
        $visitor_all[] = Transaction::where('program_id', $id)->where('status', 'success')->count();

        $donate_all[] = Transaction::where('program_id', $id)->sum('nominal_final');
        $donate_all[] = Transaction::where('program_id', $id)->where('status', 'success')->sum('nominal_final');
        $donate_all[] = Transaction::where('program_id', $id)->where('status', '<>', 'success')->count();
        $donate_all[] = Transaction::where('program_id', $id)->where('status', '<>', 'success')->sum('nominal_final');
        $donate_all[] = $avg_all;
        $donate_all[] = $donate_today[5];

        // Visitor landing page
        $visitor_analytic = $this->analyticVisitorShow($id);
        $a = array_filter($visitor_analytic);
        if (count($a)) {
            $visitor_analytic_avg = array_sum($a) / count($a);
        } else {
            $visitor_analytic_avg = 0;
        }

        // Visitor count klik tombol donasi
        $click_donate = $this->analyticVisitorClickDonateShow($id);

        // Visitor count donate / transaction
        $donate_count = $this->analyticVisitorDonateShow($id);

        // Visitor count donate paid / success
        $donate_paid = $this->analyticVisitorPaidShow($id);

        // Per Day
        $list_date = Transaction::where('program_id', $id)->select(DB::Raw('DATE(created_at) day'))->groupBy('day')->orderBy('day', 'DESC')->get();
        // foreach ($list_date as $key => $value) {
        //     echo $value->day;
        //     echo "<br><br>";
        // }

        // Per tangal
        for ($i = 0; $i < 31; $i++) {
            $dt = sprintf('%02d', $i + 1);

            $jml_date = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->groupBy(DB::raw('Date(created_at)'))
                ->count();
            $count_per_date = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->count();
            $per_date_count[] = $count_per_date > 1 ? $count_per_date / $jml_date : 0;
            $per_date_nominal[] = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-' . $dt . '%')
                ->avg('nominal_final');
        }

        // Per Jam (MASIH SALAH)
        $first_trans_date = Transaction::where('program_id', $id)->orderBy('created_at', 'asc')->select('created_at')->first()->created_at;
        $from = \Carbon\Carbon::parse($first_trans_date);
        $to = \Carbon\Carbon::parse($dn);
        $diff_in_days = $to->diffInDays($from);

        for ($i = 0; $i < 24; $i++) {
            $dt = sprintf('%02d', $i);

            $count_per_time = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-__ ' . $dt . '%')
                ->count();
            $per_time_count[] = $count_per_time > 1 ? round($count_per_time / $diff_in_days, 2) : 0;
            $per_time_nominal[] = Transaction::where('program_id', $id)
                ->where('created_at', 'like', '____-__-__ ' . $dt . '%')
                ->avg('nominal_final');
        }

        return view('admin.program.stat', compact('program_name', 'visitor_today', 'donate_today', 'summary', 'visitor_all', 'donate_all', 'id', 'visitor_analytic', 'visitor_analytic_avg', 'click_donate', 'donate_count', 'donate_paid', 'per_date_count', 'per_date_nominal', 'per_time_count', 'per_time_nominal'));
    }

    /**
     * Show detail statistic Program
     */
    public function detailDonatur(Request $request)
    {
        echo 'list donatur';
        // return view('admin.program.donatur');
    }

    /**
     * Show detail statistic Program
     */
    public function detailFundraiser(Request $request)
    {
        echo 'list fundraiser';
        // return view('admin.program.fundraiser');
    }

    /**
     * Show Summary in this Program from datatable program
     */
    public function showSummary(Request $request)
    {
        $id = $request->id;
        $dn = date('Y-m-d');

        $payout_paid = \App\Models\Payout::where('status', 'paid')->where('program_id', $id)->sum('nominal_approved');
        $payout_req = \App\Models\Payout::where('status', 'request')->where('program_id', $id)->sum('nominal_approved');
        $payout_process = \App\Models\Payout::where('status', 'process')->where('program_id', $id)->sum('nominal_approved');
        $payout_reject = \App\Models\Payout::where('status', 'reject')->where('program_id', $id)->sum('nominal_approved');
        $payout_cancel = \App\Models\Payout::where('status', 'cancel')->where('program_id', $id)->sum('nominal_approved');
        $donate_sum = Transaction::select('id')->where('status', 'success')->where('program_id', $id)->sum('nominal_final');
        $ads_spent = \App\Models\ProgramSpend::where('program_id', $id)->where('type', 'ads')->where('status', 'done')->whereNotNull('date_approved')->sum('nominal_approved');
        $platform_fee = ($donate_sum * 5) / 100;
        $ads_fee = ($donate_sum * 20) / 100;
        $opex_fee = ($donate_sum * 2) / 100;

        $optimation_fee = \App\Models\Program::where('id', $id)->select('optimation_fee')->first()->optimation_fee;
        if ($optimation_fee > 0) {
            $optimation_fee_final = $donate_sum * ($optimation_fee / 100);
        } else {
            $optimation_fee_final = 0;
        }

        // hitung mana yg lebih besar ads_spent atau 20% anggaran ads, maka itu yg dimasukkan hitungan pengurangan penghimpunan
        if ($ads_spent > $ads_fee) {
            $final_ads_fee = $ads_spent;
        } else {
            $final_ads_fee = $ads_fee;
        }

        $final = $donate_sum - $platform_fee - $final_ads_fee - $opex_fee - $optimation_fee_final - $payout_paid;

        $data1 =
            '<div class="row">
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <td class="text-start">Total Donasi</td>
                            <td>Rp. '
            . number_format($donate_sum)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Platform Fee 5%</td>
                            <td>Rp. '
            . number_format($platform_fee)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">ADS Fee 20%</td>
                            <td>Rp. '
            . number_format($ads_fee)
            . ' | Rp. '
            . number_format($ads_spent)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Admin Bank 2%</td>
                            <td>Rp. '
            . number_format($opex_fee)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Optimasi Fee '
            . $optimation_fee
            . '%</td>
                            <td>Rp. '
            . number_format($optimation_fee_final)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '
            . number_format($payout_paid)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Sisa Penghimpunan</td>
                            <td>Rp. '
            . number_format($final)
            . '</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-hover table-responsive mb-1">
                        <tr>
                            <th>Penyaluran</th>
                            <th>Nominal</th>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Diajukan</td>
                            <td>Rp. '
            . number_format($payout_req)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Sedang Diproses</td>
                            <td>Rp. '
            . number_format($payout_process)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Terbayar</td>
                            <td>Rp. '
            . number_format($payout_paid)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Ditolak</td>
                            <td>Rp. '
            . number_format($payout_reject)
            . '</td>
                        </tr>
                        <tr>
                            <td class="text-start">Penyaluran Dibatalkan</td>
                            <td>Rp. '
            . number_format($payout_cancel)
            . '</td>
                        </tr>
                    </table>
                </div>
            </div>';
        return $data1;
    }

    /**
     * Show Donate from datatable program
     */
    public function showSpend(Request $request)
    {
        $data = \App\Models\ProgramSpend::where('program_id', $request->id)->orderBy('created_at', 'DESC');

        $order_column = $request->input('order.0.column');
        $order_dir = $request->input('order.0.dir') ? $request->input('order.0.dir') : 'asc';

        $count_total = $data->count();

        $search = $request->input('search.value');

        $count_filter = $count_total;
        if ($search != '') {
            $data = $data->where(function ($q) use ($search) {
                $q
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('desc_request', 'like', '%' . $search . '%')
                    ->orWhere('nominal_approved', 'like', '%' . str_replace([',', '.'], '', $search) . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('date_approved', 'like', '%' . $search . '%');
            });
            $count_filter = $data->count();
        }

        $pageSize = $request->length ? $request->length : 10;
        $start = $request->start ? $request->start : 0;

        $data->skip($start)->take($pageSize);

        $data = $data->get();
        return Datatables::of($data)
            ->with([
                'recordsTotal' => $count_total,
                'recordsFiltered' => $count_filter,
            ])
            ->setOffset($start)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return date('Y-m-d H:i', strtotime($row->date_approved));
            })
            ->addColumn('title', function ($row) {
                return ucwords($row->title);
            })
            ->addColumn('nominal', function ($row) {
                return number_format($row->nominal_approved);
            })
            ->addColumn('desc', function ($row) {
                return $row->desc_request;
            })
            ->addColumn('status', function ($row) {
                if ($row->type == 'ads') {
                    $type = '<span class="badge badge-info badge-sm">ADS</span>';
                } elseif ($row->type == 'operational') {
                    $type = '<span class="badge badge-warning badge-sm">OPERASIONAL</span>';
                } elseif ($row->type == 'payment_fee') {
                    $type = '<span class="badge badge-primary badge-sm">PAYMENT FEE</span>';
                } else {
                    // others
                    $type = '<span class="badge badge-success badge-sm">OTHERS</span>';
                }

                if ($row->status == 'draft') {
                    $status = '<span class="badge badge-info badge-sm">PENGAJUAN</span>';
                } elseif ($row->status == 'process') {
                    $status = '<span class="badge badge-warning badge-sm">DIPROSES</span>';
                } elseif ($row->status == 'done') {
                    $status = '<span class="badge badge-success badge-sm">SELESAI</span>';
                } elseif ($row->status == 'cancel') {
                    $status = '<span class="badge badge-secondary badge-sm">DIBATALKAN</span>';
                } else {
                    // reject
                    $status = '<span class="badge badge-danger badge-sm">DITOLAK</span>';
                }

                return $type . ' ' . $status;
            })
            ->rawColumns(['date', 'title', 'nominal', 'desc', 'status'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function submitSpend(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'id_program' => 'required|numeric',
            'date_time' => 'required',
            'nominal' => 'required',
        ]);

        $data = new \App\Models\ProgramSpend();
        $data->program_id = $request->id_program;
        $data->title = trim($request->title);
        $data->nominal_request = str_replace('.', '', $request->nominal);
        $data->nominal_approved = str_replace('.', '', $request->nominal);
        $data->date_request = $request->date_time . ':00';
        $data->date_approved = $request->date_time . ':00';
        $data->approved_by = 1;
        $data->type = 'ads';
        $data->is_operational = 1;
        $data->status = 'done';
        $data->desc_request = trim($request->title);
        $data->save();

        echo 'success';
        // return redirect()->back();
    }

    public function donatePerformance(Request $request)
    {
        $data = [];
        $dn = date('d-m-Y');
        $jml_day_of = 20;
        $jml_program = 40;

        $program_trans = Transaction::where('status', 'success')
            ->where('created_at', '>=', date('Y-m-d', strtotime($dn . '-5 days')) . ' 00:00:00')
            ->groupBy('program_id')
            ->pluck('program_id');

        $program = Program::select('id', 'title', 'donate_sum')
            ->where('is_publish', 1)
            ->where('end_date', '>', date('Y-m-d H:i:s'))
            // ->where(function ($q){
            //     $q->where('is_recommended', 1)->orWhere('is_show_home', 1)->orWhere('is_urgent', 1);
            // })
            ->whereIn('id', $program_trans)
            ->orderBy('donate_sum', 'DESC')
            ->limit($jml_program)
            ->get();
        foreach ($program as $v) {
            $donate = Transaction::select(DB::raw('sum(nominal_final) as sum'), DB::raw('count(id) as count'), DB::raw('DATE(created_at) as created_at'))
                ->where('program_id', $v->id)
                ->where('status', 'success')
                ->where('created_at', '>=', date('Y-m-d', strtotime($dn . '-' . $jml_day_of . ' days')) . ' 00:00:00')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'), 'DESC')
                ->limit($jml_program)
                ->get()
                ->toArray();
            $date = isset($donate[0]['created_at']) ? date('d-m-y', strtotime($donate[0]['created_at'])) : '-';
            $data[] = [
                'date' => $date,
                'title' => $v->title,
                'donate_sum' => $v->donate_sum,
                'donate' => $donate,
            ];
        }
        // dd($data);
        return view('admin.program.performance', compact('data'));
    }

    /**
     * Select2 Program
     */
    public function select2(Request $request)
    {
        $data = Program::query()->select('id', 'title', 'nominal_approved', 'slug');
        $last_page = null;

        if ($request->has('search') && $request->search != '') {
            // Apply search param
            $data = $data->where('title', 'like', '%' . $request->search . '%');
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
