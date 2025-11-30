<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $dates = ['start_date', 'end_date'];

    // relations

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // scopes

    public function scopeByRoom($query)
    {
        if (request()->filled('room_id')) {
            $query->where('room_id', request('room_id'));
        }

        return $query;
    }
}
