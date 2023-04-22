<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapyTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
