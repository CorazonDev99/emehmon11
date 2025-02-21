<?php

namespace App\Http;

use App\Http\Middleware\CheckModeratorOrAdmin;
use Illuminate\Foundation\Http\Kernel as HttpKernel;


class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'moderatorOrAdmin' => CheckModeratorOrAdmin::class,
    ];
}
