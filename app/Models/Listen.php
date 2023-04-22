<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listen extends Model
{
    use HasFactory;

    protected $fillable = [
        'listen_tag_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image'
    ];

    public function listenTag() {
        return $this->belongsTo('App\Models\ListenTag');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function listenTagMulti() {
        return $this->hasMany('App\Models\ListenTagMulti', 'listen_id');
    }

    public function favourite() {
        return $this->hasOne('App\Models\Favourite', 'community_id')->where('community_type', config('communityType.listen'));
    }
}
