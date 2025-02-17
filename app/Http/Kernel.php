<?php

namespace App\Http;

class Kernel
{
    protected $routeMiddleware = [
        'superuser' => \App\Http\Middleware\SuperUserMiddleware::class,
        'moderatorOrAdmin' => \App\Http\Middleware\CheckModeratorOrAdmin::class,
    ];
}
