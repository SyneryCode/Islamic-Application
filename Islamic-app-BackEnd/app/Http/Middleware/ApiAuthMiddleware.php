<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // تعطيل session للطلبات API لتجنب مشكلة جدول sessions
        if ($request->is('api/*')) {
            config(['session.driver' => 'array']);
        }
        
        return $next($request);
    }
}