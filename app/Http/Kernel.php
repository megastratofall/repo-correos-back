<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Otros middlewares globales
    ];

    protected $middlewareGroups = [
        'web' => [
            // Otros middlewares del grupo web
        ],
        'api' => [
            // Otros middlewares del grupo api
            \App\Http\Middleware\CorsMiddleware::class,
        ],
    ];
}