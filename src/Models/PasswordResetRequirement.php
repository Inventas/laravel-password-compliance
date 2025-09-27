<?php

namespace Inventas\PasswordCompliance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PasswordResetRequirement extends Model
{
    protected $table = 'password_reset_requirements';

    protected $fillable = [
        'model_type',
        'model_id',
        'enforce_at',
        'reason',
    ];

    protected $casts = [
        'enforce_at' => 'datetime',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
