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
        'required_until',
        'reason',
    ];

    protected $casts = [
        'required_until' => 'datetime',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
