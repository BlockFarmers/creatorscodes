<?php

namespace Azuriom\Plugin\Creatorcodes\Models;

use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorSupport extends Model
{
    protected $table = 'creatorcodes_supports';

    protected $fillable = [
        'user_id',
        'creator_code_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creatorCode(): BelongsTo
    {
        return $this->belongsTo(CreatorCode::class);
    }
}
