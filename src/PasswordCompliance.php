<?php

namespace Inventas\PasswordCompliance;

use Inventas\PasswordCompliance\Contracts\PasswordComplianceRepository;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Carbon\Carbon;

class PasswordCompliance
{
    protected PasswordComplianceRepository $repository;

    public function __construct(PasswordComplianceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function requirePasswordChange(AuthenticatableContract $user, ?Carbon $until = null, ?string $reason = null)
    {
        return $this->repository->requirePasswordChange($user, $until, $reason);
    }

    public function clearRequirement(AuthenticatableContract $user)
    {
        return $this->repository->clearRequirement($user);
    }

    public function isRequired(AuthenticatableContract $user): bool
    {
        return $this->repository->isRequired($user);
    }

    public function getFor(AuthenticatableContract $user)
    {
        return $this->repository->getFor($user);
    }
}
