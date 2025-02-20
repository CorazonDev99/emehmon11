<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpBlockMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allow_aps[] = '127.0.0.1'; //remove this line if enviroment is production;
        for ($i=1; $i<255;$i++) {
            $allow_aps[] = '10.49.0.' . $i; //SHAKHISTAN LAN
            $allow_aps[] = '10.51.0.' . $i; //C5 LAN
        }
        $ip = $request->ip();
//        if (!in_array($ip, $allow_aps)) return abort(403, 'Sizning IP manzilingizga ruxsat etilmagan!');
        return $next($request);
    }
}
