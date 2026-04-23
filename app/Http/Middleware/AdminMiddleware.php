<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function __construct(private AuthService $auth) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->auth->check() || !$this->auth->isAdmin()) {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}
