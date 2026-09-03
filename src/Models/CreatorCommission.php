<?php

namespace Azuriom\Plugin\Creatorcodes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorCommission extends Model
{
    protected $table = 'creatorcodes_commissions';

    protected $fillable = [
        'creator_code_id',
        'order_id',
        'buyer_id',
        'order_amount',
        'commission_amount',
        'currency',
        'paid_out',
        'paid_out_at',
        'paypal_batch_id',
        'paypal_status',
        'paypal_error',
    ];

    protected $casts = [
        'paid_out' => 'boolean',
        'paid_out_at' => 'datetime',
        'order_amount' => 'float',
        'commission_amount' => 'float',
    ];

    public function creatorCode(): BelongsTo
    {
        return $this->belongsTo(CreatorCode::class);
    }
}
