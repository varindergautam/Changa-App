<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapy extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapy_tag_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image'
    ];

    public function therapyTag() {
        return $this->belongsTo('App\Models\TherapyTag');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function therapyTagMulti() {
        return $this->hasMany('App\Models\TherapyTagMulti', 'therapy_id');
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'community_id')->where('community_type', config('communityType.therapy'));
    }
}
