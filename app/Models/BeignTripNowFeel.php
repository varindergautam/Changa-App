<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeignTripNowFeel extends Model
{
    use HasFactory;

    protected $fillable = [
        'beign_trip_id',
        'feeling_id',
        'feeling_text',
        'refelct',
        'trip_journal_emotion',
        'trip_journal_emotion_text',
        'trip_journal_insiahts',
        'trip_journal_insiahts_text',
        'trip_journal_vision',
        'trip_journal_vision_text',
        'trip_journal_unaudied',
        'trip_journal_unaudied_text',
        'different_tommorow',
    ];
}
