<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // relations

    public function type(): BelongsTo
    {
        return $this->belongsTo(ShiftType::class, 'shift_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // scopes

    public function scopeByDoctor($query)
    {
        return $query->when(request()->filled('doctor_id'), function ($q) {
            $q->where('user_id', request()->doctor_id);
        });
    }
}
