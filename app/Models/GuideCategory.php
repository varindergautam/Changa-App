<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'username',
        'file_type',
        'file',
        'background_image',
        'active',
    ];
}
