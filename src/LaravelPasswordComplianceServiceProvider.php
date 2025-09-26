<?php

namespace Inventas\LaravelPasswordCompliance;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Inventas\LaravelPasswordCompliance\Commands\LaravelPasswordComplianceCommand;

class LaravelPasswordComplianceServiceProvider extends PackageServiceProvider
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
            ->hasMigration('create_laravel_password_compliance_table')
            ->hasCommand(LaravelPasswordComplianceCommand::class);
    }
}
