<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTrackingToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan session tersedia
        if (!$request->hasSession()) {
            return response()->json([
                'success' => false,
                'message' => 'Session not available.'
            ], 401);
        }

        // Token dari header AJAX
        $token = $request->header('X-Tracking-Token');

        // Token dari session Laravel
        $sessionToken = $request->session()->token();

        if (
            empty($token) ||
            empty($sessionToken) ||
            !hash_equals($sessionToken, $token)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 401);
        }

        return $next($request);
    }
}