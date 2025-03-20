<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'register',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        ini_set('post_max_size', '120M');
        ini_set('upload_max_filesize', '100M');

        return parent::handle($request, $next);
    }
}
