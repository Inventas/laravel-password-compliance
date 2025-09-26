# Laravel Password Compliance

Enforce forced password resets in your Laravel application.

This package lets you mark users as requiring a password change and redirect them to a dedicated change-password page using a middleware. It provides a small programmatic API, a convenient trait for your Authenticatable model, and a middleware alias you can attach to routes.


## Install

```bash
composer require inventas/laravel-password-compliance
```

After installing the package you can publish the config file and the migration (the package uses a migration stub which will be published into your application's `database/migrations` folder when you run the vendor:publish command):

```bash
php artisan vendor:publish --provider="Inventas\PasswordCompliance\PasswordComplianceServiceProvider" --tag="config"
php artisan vendor:publish --provider="Inventas\PasswordCompliance\PasswordComplianceServiceProvider" --tag="migrations"
```

Then run the migrations:

```bash
php artisan migrate
```


## Overview

- Programmatic API to require a user to change their password.
- Middleware alias `password.compliance` which redirects users that must change their password to a configured route or URL.
- A trait `RequiresPasswordChange` you can add to your Authenticatable model for convenience methods and relationship access.


## Configuration

The published config file is `config/password-compliance.php` and contains a few options:

- `redirect_route` — optional route name to redirect users to (recommended)
- `redirect_url` — fallback URL when `redirect_route` is not set
- `exempt_routes` — an array of route names which should be exempt from the middleware (useful for the change-password route itself)
- `guard` — optional guard name to check for the authenticated user

Example (config/password-compliance.php):

```php
return [
    'redirect_route' => 'password.change',
    'redirect_url' => '/password/change',
    'exempt_routes' => ['password.change'],
    'guard' => null,
];
```


## Usage

### Trait

Add the trait to your `User` model:

```php
use Inventas\PasswordCompliance\Traits\RequiresPasswordChange;

class User extends Authenticatable
{
    use RequiresPasswordChange;
}
```

This trait provides these convenience methods:

- `$user->requirePasswordChange($until = null, $reason = null)` — mark the user as required to change their password until an optional time (pass `null` for indefinite).
- `$user->clearPasswordRequirement()` — clear the requirement for the user.
- `$user->isPasswordChangeRequired()` — check whether the user currently must change their password.
- `$user->passwordCompliance()` — a `morphOne` relation to the underlying database record.


### Programmatic API / Facade

The package exposes a small service and a facade. Use the facade if you prefer a static-looking interface:

```php
use Inventas\PasswordCompliance\Facades\PasswordCompliance;

// require indefinitely
PasswordCompliance::requirePasswordChange($user, null, 'Admin forced reset');

// require until a date
PasswordCompliance::requirePasswordChange($user, now()->addDays(7));

// clear
PasswordCompliance::clearRequirement($user);
```


### Middleware

The middleware alias `password.compliance` is registered by the package. Attach it to routes or route groups to ensure users who are required to change their password are redirected:

```php
Route::middleware(['auth', 'password.compliance'])->group(function () {
    // protected routes
});
```

The middleware will:
- skip unauthenticated requests
- skip API/JSON requests by default (so APIs won't receive HTML redirects)
- skip routes listed in `exempt_routes` in the config
- redirect to the named route in `redirect_route` or to `redirect_url` if the route is not set

## Testing

The package includes Pest tests. Run them with:

```bash
vendor/bin/pest
```


## Contributing

Contributions are very welcome. Please open issues or PRs. If you add functionality, include tests (Pest) and keep backwards compatibility where possible.


## License

The package is open-sourced software licensed under the MIT license.
