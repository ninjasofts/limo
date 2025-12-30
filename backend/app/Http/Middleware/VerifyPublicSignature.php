<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class VerifyPublicSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = (string) $request->header('X-Client', '');
        $timestamp = (string) $request->header('X-Timestamp', '');
        $signature = (string) $request->header('X-Signature', '');
        $nonce = (string) $request->header('X-Nonce', '');

        // 1) Validate client
        $allowedClients = config('limoflux.public_clients', []);
        if ($client === '' || ! in_array($client, $allowedClients, true)) {
            return response()->json([
                'message' => 'Unauthorized client.',
            ], 401);
        }

        // 2) Validate secret exists
        $secret = (string) config('limoflux.public_api_secret', '');
        if ($secret === '') {
            return response()->json([
                'message' => 'Server misconfigured (missing public API secret).',
            ], 500);
        }

        // 3) Validate timestamp
        if ($timestamp === '' || ! ctype_digit($timestamp)) {
            return response()->json([
                'message' => 'Missing or invalid timestamp.',
            ], 401);
        }

        $ts = (int) $timestamp;
        $maxSkew = (int) config('limoflux.signature.max_clock_skew_seconds', 300);
        $now = time();

        if (abs($now - $ts) > $maxSkew) {
            return response()->json([
                'message' => 'Request expired (timestamp skew too large).',
            ], 401);
        }

        // 4) Optional: replay protection with nonce
        $requireNonce = (bool) config('limoflux.signature.require_nonce', false);
        $nonceTtl = (int) config('limoflux.signature.nonce_ttl_seconds', 300);

        if ($requireNonce && $nonce === '') {
            return response()->json([
                'message' => 'Missing nonce.',
            ], 401);
        }

        if ($nonce !== '') {
            $cacheKey = 'publicsig:' . $client . ':' . $nonce;

            // If exists, it's a replay
            if (Cache::has($cacheKey)) {
                return response()->json([
                    'message' => 'Replay detected.',
                ], 401);
            }

            // Store nonce for TTL
            Cache::put($cacheKey, 1, $nonceTtl);
        }

        // 5) Compute expected signature
        $expected = $this->computeSignature($request, $secret, $client, $ts);

        if ($signature === '' || ! hash_equals($expected, $signature)) {
            return response()->json([
                'message' => 'Invalid signature.',
            ], 401);
        }

        return $next($request);
    }

    private function computeSignature(Request $request, string $secret, string $client, int $timestamp): string
    {
        $method = strtoupper($request->getMethod());

        // Canonical path + stable query string
        $path = $request->getPathInfo();

        $queryParams = $request->query->all();
        if (! empty($queryParams)) {
            ksort($queryParams);
            $query = http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
            $path .= '?' . $query;
        }

        $rawBody = (string) $request->getContent();

        // IMPORTANT: This exact format must be mirrored in WordPress
        $base = implode('|', [
            $client,
            (string) $timestamp,
            $method,
            $path,
            $rawBody,
        ]);

        return hash_hmac('sha256', $base, $secret);
    }
}
