<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediate_tag_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image',
    ];

    public function mediateTag() {
        return $this->belongsTo('App\Models\MediateTag');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function mediateTagMulti() {
        return $this->hasMany('App\Models\MediateTagMulti', 'mediate_id');
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'community_id')->where('community_type', config('communityType.mediate'));
    }
}
