<?php

namespace Inventas\PasswordCompliance\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Carbon\Carbon;
use Inventas\PasswordCompliance\PasswordCompliance as ComplianceService;
use Inventas\PasswordCompliance\Models\PasswordResetRequirement;

trait RequiresPasswordChange
{
    /**
     * MorphOne relation to the password compliance record.
     */
    public function passwordCompliance(): MorphOne
    {
        return $this->morphOne(PasswordResetRequirement::class, 'model');
    }

    /**
     * Require the current model to change password until an optional time.
     * Accepts a Carbon instance, a DateTimeInterface or a parsable date string.
     *
     * @param mixed $until
     * @param string|null $reason
     * @return mixed
     */
    public function requirePasswordChange($until = null, ?string $reason = null)
    {
        if ($until !== null && ! $until instanceof Carbon) {
            $until = Carbon::parse($until);
        }

        return app(ComplianceService::class)
            ->requirePasswordChange($this, $until, $reason);
    }

    /**
     * Clear the password change requirement for the current model.
     */
    public function clearPasswordRequirement()
    {
        return app(ComplianceService::class)
            ->clearRequirement($this);
    }

    /**
     * Check whether the current model is required to change its password.
     */
    public function isPasswordChangeRequired(): bool
    {
        return app(ComplianceService::class)
            ->isRequired($this);
    }
}
