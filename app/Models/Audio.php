<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $table = 'audios';

    protected $fillable = [
        'audio_tag_id',
        'user_id',
        'title',
        'description',
        'active',
        'file',
        'file_type',
        'background_image',
    ];

    public function AudioTag() {
        return $this->belongsTo('App\Models\AudioTag');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function audioTagMulti() {
        return $this->hasMany('App\Models\AudioTagMulti', 'audio_id');
    }
}
