<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Cek apakah user yang login memiliki role admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Hanya admin yang bisa mengakses endpoint ini.',
                'data'    => null,
            ], 403);
        }

        return $next($request);
    }
}
