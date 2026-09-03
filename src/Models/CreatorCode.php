<?php

namespace Azuriom\Plugin\Creatorcodes\Models;

use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorCode extends Model
{
    protected $table = 'creatorcodes_codes';

    protected $fillable = [
        'user_id',
        'code',
        'commission_rate',
        'paypal_email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'commission_rate' => 'float',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(CreatorCommission::class);
    }

    public function totalCommission(): float
    {
        return (float) $this->commissions()->sum('commission_amount');
    }

    public function pendingCommission(): float
    {
        return (float) $this->commissions()->where('paid_out', false)->sum('commission_amount');
    }
}
