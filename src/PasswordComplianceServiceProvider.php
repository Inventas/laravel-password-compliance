<?php

namespace Inventas\PasswordCompliance;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Inventas\PasswordCompliance\Contracts\PasswordComplianceRepository;
use Inventas\PasswordCompliance\Repositories\EloquentPasswordComplianceRepository;
use Inventas\PasswordCompliance\Middleware\EnsurePasswordReset;

class PasswordComplianceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-password-compliance')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_password_reset_requirements_table');
    }

    public function register(): void
    {
        // Bind the repository contract to the Eloquent implementation
        $this->app->bind(
            PasswordComplianceRepository::class,
            EloquentPasswordComplianceRepository::class
        );

        // Bind the main service so it can be resolved via the facade or container
        $this->app->singleton(PasswordCompliance::class, function ($app) {
            return new PasswordCompliance($app->make(PasswordComplianceRepository::class));
        });
    }

    public function boot(): void
    {
        // Register a middleware alias so package consumers can refer to it easily
        $router = $this->app->make('router');
        $router->aliasMiddleware('password.compliance', EnsurePasswordReset::class);
    }
}
