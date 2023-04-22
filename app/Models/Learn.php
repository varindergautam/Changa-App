<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learn extends Model
{
    use HasFactory;

    protected $fillable = [
        'learn_tag_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image',
    ];

    public function learnTag() {
        return $this->belongsTo('App\Models\LearnTag');
    }

    public function learnTagMulti() {
        return $this->hasMany('App\Models\LearnTagMulti', 'learn_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'community_id')->where('community_type', config('communityType.learn'));
    }
}
