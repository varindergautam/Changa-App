<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'end_trip_remainder',
        'new_content',
        'trip_update',
        'reflect_after_trip',
        'review_narrative_identity',
    ];
}
