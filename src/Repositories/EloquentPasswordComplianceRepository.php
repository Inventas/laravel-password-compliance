<?php

namespace Inventas\PasswordCompliance\Repositories;

use Inventas\PasswordCompliance\Contracts\PasswordComplianceRepository;
use Inventas\PasswordCompliance\Models\PasswordResetRequirement;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Carbon\Carbon;

class EloquentPasswordComplianceRepository implements PasswordComplianceRepository
{
    public function requirePasswordChange(AuthenticatableContract $user, ?Carbon $until = null, ?string $reason = null)
    {
        $attributes = [
            'model_type' => get_class($user),
            'model_id' => $user->getAuthIdentifier(),
        ];

        $values = [
            'required_until' => $until?->toDateTimeString(),
            'reason' => $reason,
        ];

        return PasswordResetRequirement::updateOrCreate($attributes, array_filter($values, fn($v) => $v !== null));
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

        if ($record->required_until === null) {
            return true;
        }

        return $record->required_until->isFuture();
    }

    public function getFor(AuthenticatableContract $user)
    {
        return PasswordResetRequirement::where('model_type', get_class($user))
            ->where('model_id', $user->getAuthIdentifier())
            ->first();
    }
}
