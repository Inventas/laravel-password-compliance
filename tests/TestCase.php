<?php

namespace Inventas\PasswordCompliance\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Inventas\PasswordCompliance\PasswordComplianceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Inventas\\PasswordCompliance\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            PasswordComplianceServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            // When enforcement should start (null = enforce immediately)
            $table->timestamp('enforce_at')->nullable();
            // Optional reason or metadata
            $table->string('reason')->nullable();
            $table->timestamps();
            $table->unique(['model_type', 'model_id'], 'password_reset_requirements_model_unique');
        });
    }
}
