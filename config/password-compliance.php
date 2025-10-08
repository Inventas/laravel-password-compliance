<?php

return [
    // Route name to redirect users who must change their password. If null, `redirect_url` will be used.
    'redirect_route' => null,

    // Fallback URL to redirect users to change their password.
    'redirect_url' => '/password/change',

    // URL to redirect users if they are not required to change their password (e.g. the dashboard) but visit a password change page.
    'home_url' => '/dashboard',

    // List of route names that should be exempt from the middleware (e.g. the change password route itself)
    'exempt_routes' => [
        // 'password.change',
    ],

    // Optional guard to check for the authenticated user (default uses the default guard)
    'guard' => null,
];
