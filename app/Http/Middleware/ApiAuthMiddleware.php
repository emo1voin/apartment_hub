<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    public function __construct(private AuthService $auth) {}

    public function handle(Request $request, Closure $next)
    {
        if (!$this->auth->check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему');
        }

        return $next($request);
    }
}
