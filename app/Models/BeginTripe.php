<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeginTripe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feeling_id',
        'feeling_text',
        'intention_id',
        'intention_text',
        'type_of_trip',
        'satisfaction_type',
        'satisfaction_text',
        'visual_id',
        'audio_tag_id',
        'audio_id',
        'time_of_recording',
        'trip_start_date',
        'trip_end_date',
        'trip_icon',
        'day'
    ];
}
