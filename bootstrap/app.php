<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // DAFTARKAN CLASS MIDDLEWARE BARU SECARA AMAN DI SINI
        // $middleware->append(\App\Http\Middleware\BlockAdminMiddleware::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($e instanceof ValidationException) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($status === 404) {
                return null;
            }

            Log::error($e);

            $defaultMessage = $request->isMethod('get')
                ? 'Terjadi kesalahan saat memuat halaman. Silakan coba beberapa saat lagi.'
                : 'Terjadi kesalahan saat menyimpan data. Silakan cek kembali inputnya atau coba beberapa saat lagi.';

            $message = config('app.debug')
                ? 'Terjadi kesalahan: '.$e->getMessage()
                : $defaultMessage;

            if (! $request->isMethod('get')) {
                return back()->withInput()->with('error', $message);
            }

            return response()->view('errors.friendly', [
                'message' => $message,
                'status' => $status,
            ], $status);
        });
    })->create();
