<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowedOrigins = ['http://localhost:5173'];

        if (in_array($origin, $allowedOrigins)) {
            $headers = [
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Origin, Accept',
                'Access-Control-Allow-Credentials' => 'true'
            ];

            // Si la solicitud incluye credenciales, establece el valor de Access-Control-Allow-Origin específicamente
            if ($request->headers->has('Cookie')) {
                $headers['Access-Control-Allow-Origin'] = $origin;
            } else {
                $headers['Access-Control-Allow-Origin'] = '*';
            }

            // Handle preflight OPTIONS request
            if ($request->isMethod('OPTIONS')) {
                return response()->json('{"method":"OPTIONS"}', 200, $headers);
            }
        }

        $response = $next($request);

        // Set CORS headers for the response
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}