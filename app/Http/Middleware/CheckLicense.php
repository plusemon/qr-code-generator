<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LicenseService;

class CheckLicense
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->licenseService->isInstalled()) {
            session()->put('intended.url', $request->fullUrl());
            return redirect()->route('license.form');
        }

        if (!$this->licenseService->isActive()) {
            session()->put('intended.url', $request->fullUrl());
            return redirect()->route('license.form')->withErrors(['license_key' => 'Your application license has expired. Please Contact Support.']);
        }

        return $next($request);
    }
}