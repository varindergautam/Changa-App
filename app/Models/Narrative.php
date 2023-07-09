<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narrative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'narrative',
    ];
}
