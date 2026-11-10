<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                // HTTP status codes standard
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validasi gagal.',
                        'data' => null,
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthenticated.',
                        'data' => null,
                        'errors' => null,
                    ], 401);
                }

                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage() ?: 'Akses ditolak: Anda tidak memiliki hak akses untuk permohonan ini.',
                        'data' => null,
                        'errors' => null,
                    ], 403);
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data permohonan tidak ditemukan.',
                        'data' => null,
                        'errors' => null,
                    ], 404);
                }

                // Prevent SQL queries, paths, and sensitive database trace leakage in production
                $isProduction = !config('app.debug');
                $message = $isProduction ? 'Terjadi kesalahan internal pada server.' : $e->getMessage();
                $errors = $isProduction ? null : [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];

                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'data' => null,
                    'errors' => $errors,
                ], $status >= 400 && $status < 600 ? $status : 500);
            }
        });
    })->create();
