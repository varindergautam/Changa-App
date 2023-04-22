<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'text_1',
        'text_2',
    ];
}
