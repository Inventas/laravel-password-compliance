<?php

namespace Inventas\LaravelPasswordCompliance\Commands;

use Illuminate\Console\Command;

class LaravelPasswordComplianceCommand extends Command
{
    public $signature = 'laravel-password-compliance';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
