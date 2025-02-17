<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckModeratorOrAdmin extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole(['Модератор', 'Сисадмины'])) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'У вас нет доступа к этой странице.');
    }
}
