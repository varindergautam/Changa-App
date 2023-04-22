<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image'
    ];


    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function guideCategory() {
        return $this->belongsTo('App\Models\GuideCategory', 'guide_id');
    }
}
