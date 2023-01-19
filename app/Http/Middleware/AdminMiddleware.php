<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!(currentUser() instanceof Admin)) {
            throw new CustomException('شما اجاره دسترسی ندارید.');
        }
        return $next($request);
    }
}
