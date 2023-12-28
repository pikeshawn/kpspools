<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Serviceman
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        dd($request->user());

//        return $request->expectsJson() ? null : route('login');

//        if ($request->user() === null){
//            return redirect(RouteServiceProvider::LOGIN);
//        }


//        dd($type . "  " . $type1);
//        dd($request);
//        dd($request->user());
        if ($request->user() !== null) {
            if ($request->user()->type === 'serviceman') {
                return $next($request);
            } else if ($request->user()->type === 'prospective') {
                return redirect('/prospective');
            }
        } else {
            return redirect('/dashboard');
        }
        return redirect('/');
    }
}
