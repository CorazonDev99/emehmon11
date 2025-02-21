<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckModeratorOrAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole(['Модератор', 'Сисадмины'])) {
            return $next($request);
        }

        return redirect('/')->with('error', 'У вас нет доступа к этой странице.');
    }
}


