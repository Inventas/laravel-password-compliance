<?php

namespace Inventas\PasswordCompliance\Contracts;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

interface PasswordComplianceRepository
{
    /**
     * Mark the given user as requiring a password change. The second parameter is
     * treated as the time when enforcement should start (enforce_at). Passing
     * null means enforce immediately.
     *
     * @return mixed
     */
    public function requirePasswordChange(AuthenticatableContract $user, ?Carbon $until = null, ?string $reason = null);

    /**
     * Clear the password change requirement for the given user.
     *
     * @return mixed
     */
    public function clearRequirement(AuthenticatableContract $user);

    /**
     * Check whether the user currently needs to change their password.
     */
    public function isRequired(AuthenticatableContract $user): bool;

    /**
     * Get the compliance record for the user or null.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getFor(AuthenticatableContract $user);
}
