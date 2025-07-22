<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LogsController extends Controller
{
    public function index()
{
    $logPath = storage_path('logs');
    $logFiles = [];
    $todayLog = 'laravel-' . date('Y-m-d') . '.log';

    try {
        $files = File::files($logPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $logFiles[] = $file->getFilename();
            }
        }

        // Urutkan berdasarkan tanggal terbaru
        usort($logFiles, function ($a, $b) use ($logPath) {
            return filemtime($logPath.'/'.$b) - filemtime($logPath.'/'.$a);
        });

    } catch (\Exception $e) {
        report($e);
        $logFiles = [];
    }

    return view('admin.logs.index', [
        'logFiles' => $logFiles,
        'defaultLog' => file_exists($logPath.'/'.$todayLog) ? $todayLog : ($logFiles[0] ?? null)
    ]);
}

    public function show($filename)
    {
        // Validasi nama file untuk keamanan
        if (!preg_match('/^laravel-\d{4}-\d{2}-\d{2}\.log$/', $filename)) {
            abort(400, 'Invalid log file name');
        }

        $logPath = storage_path('logs/'.$filename);

        if (!file_exists($logPath)) {
            abort(404, 'Log file not found');
        }

        // Gunakan streaming untuk file besar
        $response = new StreamedResponse(function() use ($logPath) {
            $handle = fopen($logPath, 'r');
            if ($handle) {
                while (!feof($handle)) {
                    echo fread($handle, 8192);
                    ob_flush();
                    flush();
                }
                fclose($handle);
            }
        });

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
