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

        if ($request->user() !== null) {
            if ($request->user()->type === 'serviceman') {
                return $next($request);
            } else if ($request->user()->type === 'prospective') {
                return redirect('/prospective');
            } else if ($request->user()->type === 'customer') {
                return redirect('/customer/dashboard');
            }
        } else {
            return redirect('/dashboard');
        }
        return redirect('/');
    }
}
