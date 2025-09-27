<?php

namespace Inventas\PasswordCompliance\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Inventas\PasswordCompliance\Contracts\PasswordComplianceRepository;
use Inventas\PasswordCompliance\Models\PasswordResetRequirement;

class EloquentPasswordComplianceRepository implements PasswordComplianceRepository
{
    public function requirePasswordChange(AuthenticatableContract $user, ?Carbon $until = null, ?string $reason = null)
    {
        $attributes = [
            'model_type' => get_class($user),
            'model_id' => $user->getAuthIdentifier(),
        ];

        $values = [
            'enforce_at' => $until?->toDateTimeString(),
            'reason' => $reason,
        ];

        return PasswordResetRequirement::updateOrCreate($attributes, $values);
    }

    public function clearRequirement(AuthenticatableContract $user)
    {
        return PasswordResetRequirement::where('model_type', get_class($user))
            ->where('model_id', $user->getAuthIdentifier())
            ->delete();
    }

    public function isRequired(AuthenticatableContract $user): bool
    {
        $record = PasswordResetRequirement::where('model_type', get_class($user))
            ->where('model_id', $user->getAuthIdentifier())
            ->first();

        if (! $record) {
            return false;
        }

        // enforce immediately if NULL, otherwise enforce when now >= enforce_at
        if ($record->enforce_at === null) {
            return true;
        }

        return now()->timestamp >= $record->enforce_at->timestamp;
    }

    public function getFor(AuthenticatableContract $user)
    {
        return PasswordResetRequirement::where('model_type', get_class($user))
            ->where('model_id', $user->getAuthIdentifier())
            ->first();
    }
}
