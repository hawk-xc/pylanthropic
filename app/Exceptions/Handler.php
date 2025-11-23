<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
    //         return $this->unauthenticated($request, $exception);
    //     }

    //     if ($exception instanceof \Illuminate\Validation\ValidationException) {
    //         return $this->convertValidationExceptionToResponse($exception, $request);
    //     }

    //     if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
    //         $statusCode = $exception->getStatusCode();
    //         $message = $exception->getMessage() ?: $this->getDefaultMessage($statusCode);

    //         return response()->view(
    //             'errors.error',
    //             [
    //                 'code' => $statusCode,
    //                 'message' => $message,
    //             ],
    //             $statusCode,
    //         );
    //     }

    //     return response()->view(
    //         'errors.error',
    //         [
    //             'code' => 500,
    //             'message' => $exception->getMessage() ?: 'Terjadi kesalahan pada server.',
    //         ],
    //         500,
    //     );
    // }

    protected function getDefaultMessage($code)
    {
        return match ($code) {
            403 => 'Akses ditolak. Kamu tidak memiliki izin untuk halaman ini.',
            404 => 'Halaman yang kamu cari tidak ditemukan.',
            500 => 'Terjadi kesalahan pada server.',
            default => 'Terjadi kesalahan yang tidak diketahui.',
        };
    }
}
