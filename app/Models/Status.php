<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory, SoftDeletes;

    const RESERVED = 1;

    const IN_PROGRESS = 2;
    const IN_DELAY = 3;
    const CANCELLED = 4;

    const FINISHED = 5;
}
