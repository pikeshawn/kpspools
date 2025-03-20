<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

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

    public function handle($request, Closure $next)
    {
        ini_set('post_max_size', '120M');
        ini_set('upload_max_filesize', '100M');

        return parent::handle($request, $next);
    }
}
