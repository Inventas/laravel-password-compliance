<?php

namespace Inventas\PasswordCompliance\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Inventas\PasswordCompliance\PasswordCompliance as ComplianceService;

class PreventPasswordResetIfNotRequired
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

        // If the user is not required to change the password, redirect away
        $service = app(ComplianceService::class);

        if (! $service->isRequired($user)) {
            // Redirect to intended/default location. Use configured redirect_url or root.
            $redirectUrl = $config['redirect_url'] ?? '/';

            // If a redirect_route is configured, use that route name's URL
            $redirectRoute = $config['redirect_route'] ?? null;
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
