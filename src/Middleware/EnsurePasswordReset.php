<?php

namespace Inventas\PasswordCompliance\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Inventas\PasswordCompliance\PasswordCompliance as ComplianceService;

class EnsurePasswordReset
{
    public function handle(Request $request, Closure $next)
    {
        $config = Config::get('password-compliance', []);

        $guard = $config['guard'] ?? null;
        $auth = $guard ? Auth::guard($guard) : Auth::guard();

        $user = $auth->user();

        // Nothing to do when not authenticated
        if (! $user) {
            return $next($request);
        }

        // Skip JSON/API requests
        if ($request->expectsJson()) {
            return $next($request);
        }

        // Skip exempt routes
        $routeName = optional($request->route())->getName();
        $exempt = $config['exempt_routes'] ?? [];
        if ($routeName && in_array($routeName, $exempt, true)) {
            return $next($request);
        }

        // If a user is required to change the password, redirect to the configured route / url
        $service = app(ComplianceService::class);

        if ($service->isRequired($user)) {
            // Avoid redirect loop: if the current route is the redirect_route, let through
            $redirectRoute = $config['redirect_route'] ?? null;
            $redirectUrl = $config['redirect_url'] ?? '/password/change';

            if ($routeName && $redirectRoute && $routeName === $redirectRoute) {
                return $next($request);
            }

            if ($redirectRoute) {
                $target = route($redirectRoute);
            } else {
                $target = $redirectUrl;
            }

            return redirect($target);
        }

        return $next($request);
    }
}
