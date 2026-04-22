<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAllRequests
{
    public function handle(Request $request, Closure $next)
    {
        // Логируем ВСЕ POST запросы
        if ($request->isMethod('POST')) {
            Log::info('=== POST REQUEST RECEIVED ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'path' => $request->path(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $request->all(),
            ]);
        }

        return $next($request);
    }
}
