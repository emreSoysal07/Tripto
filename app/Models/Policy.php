<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policy extends Model
{
    protected $fillable = [
        'property_id',
        'check_in_time',
        'check_out_time',
        'cancellation_policy',
        'house_rules',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}