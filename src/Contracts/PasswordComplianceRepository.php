<?php

namespace Inventas\PasswordCompliance\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Carbon\Carbon;

interface PasswordComplianceRepository
{
    /**
     * Mark the given user as requiring a password change until an optional timestamp.
     *
     * @param AuthenticatableContract $user
     * @param Carbon|null $until
     * @param string|null $reason
     * @return mixed
     */
    public function requirePasswordChange(AuthenticatableContract $user, ?Carbon $until = null, ?string $reason = null);

    /**
     * Clear the password change requirement for the given user.
     *
     * @param AuthenticatableContract $user
     * @return mixed
     */
    public function clearRequirement(AuthenticatableContract $user);

    /**
     * Check whether the user currently needs to change their password.
     *
     * @param AuthenticatableContract $user
     * @return bool
     */
    public function isRequired(AuthenticatableContract $user): bool;

    /**
     * Get the compliance record for the user or null.
     *
     * @param AuthenticatableContract $user
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getFor(AuthenticatableContract $user);
}

