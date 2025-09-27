<?php

namespace Inventas\PasswordCompliance\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Inventas\PasswordCompliance\Models\PasswordResetRequirement;
use Inventas\PasswordCompliance\PasswordCompliance as ComplianceService;

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
     * Require the current model to change password. The argument is treated as the enforce_at timestamp (null = enforce immediately).
     * Accepts a Carbon instance, a DateTimeInterface or a parsable date string.
     *
     * @param  mixed  $enforceAt
     * @return mixed
     */
    public function requirePasswordChange($enforceAt = null, ?string $reason = null)
    {
        if ($enforceAt !== null && ! $enforceAt instanceof Carbon) {
            $enforceAt = Carbon::parse($enforceAt);
        }

        return app(ComplianceService::class)
            ->requirePasswordChange($this, $enforceAt, $reason);
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
