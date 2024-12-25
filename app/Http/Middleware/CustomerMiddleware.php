<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use App\Models\Customer;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        dd($request);

        if ($request->user() !== null) {
            if ($request->user()->type === 'customer') {
                if (!$request->user()->terms_and_conditions) {
                    if($request->terms) {
                        Customer::saveTerms(Auth::user());
                        return redirect('/dashboard');
                    }
                    return redirect('/termSigning');
                }
                if (!$request->user()->privacy_policy) {

                    if($request->privacy) {
                        Customer::savePrivacyPolicy(Auth::user());
                        return redirect('/dashboard');
                    }

                    return redirect('/prospective/privacy');
                }

//                if (!$request->user()->stripe_id) {
//                    return redirect('/billing/setup');
//                }


                return $next($request);
            }
        }

        return redirect('/');
    }
}
