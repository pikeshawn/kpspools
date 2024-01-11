<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() !== null) {
            if ($request->user()->type === 'customer') {

                if (!$request->user()->terms_and_conditions) {
                    return redirect('/termSigning');
                }

                if (!$request->user()->privacy_policy) {
                    return redirect('/prospective/privacy');
                }

                if (!$request->user()->stripe_id) {
                    return redirect('/billing/setup');
                }


                return $next($request);
            }
        }

        return redirect('/');
    }
}
