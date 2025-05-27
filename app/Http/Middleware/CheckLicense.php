<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LicenseService;

class CheckLicense
{
    public function handle(Request $request, Closure $next)
    {
        $license = app(LicenseService::class);

        if (!$license->isValid()) {
            if ($request->is('activate*') || $request->is('license*')) {
                return $next($request);
            }
            return redirect()->route('license.required');
        }

        return $next($request);
    }
}
