<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediatorCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
