# Laravel Password Compliance

A small Laravel package to enforce forced password resets for users.

Features
- Programmatic API to require a user to change their password
- Middleware `password.compliance` to redirect users that must change their password
- A trait to add convenience methods and relationship to your Authenticatable model

Installation
1. Install the package (via Composer in your app):

    composer require inventas/laravel-password-compliance

2. Publish config and migration:

    php artisan vendor:publish --provider="Inventas\PasswordCompliance\PasswordComplianceServiceProvider" --tag="config"
    php artisan vendor:publish --provider="Inventas\PasswordCompliance\PasswordComplianceServiceProvider" --tag="migrations"

3. Run migrations:

    php artisan migrate

Configuration
- `config/password-compliance.php` includes:
  - `redirect_route` (optional) — route name to redirect to
  - `redirect_url` — fallback URL
  - `exempt_routes` — route names to exempt from middleware (avoid redirect loops)
  - `guard` — optional guard name

Usage
- Trait
  Add the trait to your `User` model:

  ```php
  use Inventas\PasswordCompliance\Traits\RequiresPasswordChange;

  class User extends Authenticatable
  {
      use RequiresPasswordChange;
  }
  ```

  Then use the convenience methods:

  ```php
  // require until a date
  $user->requirePasswordChange(now()->addDays(7), 'Expired');

  // check
  $user->isPasswordChangeRequired();

  // clear after successful change
  $user->clearPasswordRequirement();
  ```

- Programmatic API / Facade

  ```php
  use Inventas\PasswordCompliance\Facades\PasswordCompliance;

  PasswordCompliance::requirePasswordChange($user, null, 'Admin forced reset');
  ```

- Middleware

  Apply middleware alias `password.compliance` to routes or route groups:

  ```php
  Route::middleware(['auth', 'password.compliance'])->group(function () {
      // protected routes
  });
  ```

Testing
- The package includes Pest tests. Run them with:

    vendor/bin/pest

Notes
- If your users use UUIDs or non-integer primary keys, update the migration's `model_id` column accordingly.

Contributing
- PRs welcome. Add tests for new behavior and keep backward compatibility.

License
- MIT

