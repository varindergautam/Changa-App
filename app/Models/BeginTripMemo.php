<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeginTripMemo extends Model
{
    use HasFactory;

    protected $fillable = [
        'begin_tripe_id',
        'voice_memo',
        'guide_id',
        'time_of_recording',
        'file_type',
    ];
}
