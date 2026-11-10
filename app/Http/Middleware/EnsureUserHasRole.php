<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
                'data' => null,
                'errors' => null,
            ], 401);
        }

        $userRole = strtolower($user->role ?? '');

        // Standardize role synonyms / aliases
        $normalizedRole = match ($userRole) {
            'admin', 'admin_disperwaskim', 'administrator' => 'admin_disperwaskim',
            'verifikator' => 'verifikator',
            'tim_monitoring', 'perwaskim', 'tim_lapangan' => 'perwaskim',
            'pengembang', 'developer' => 'pengembang',
            'pimpinan', 'kepala_dinas' => 'pimpinan',
            default => $userRole,
        };

        $allowedRoles = array_map(function ($r) {
            $lower = strtolower(trim($r));
            return match ($lower) {
                'admin', 'admin_disperwaskim', 'administrator' => 'admin_disperwaskim',
                'verifikator' => 'verifikator',
                'tim_monitoring', 'perwaskim', 'tim_lapangan' => 'perwaskim',
                'pengembang', 'developer' => 'pengembang',
                'pimpinan', 'kepala_dinas' => 'pimpinan',
                default => $lower,
            };
        }, $roles);

        if (!in_array($normalizedRole, $allowedRoles, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak: Anda tidak memiliki wewenang untuk menjalankan aksi ini.',
                'data' => null,
                'errors' => null,
            ], 403);
        }

        return $next($request);
    }
}

