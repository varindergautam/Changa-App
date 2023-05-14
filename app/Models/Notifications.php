<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'notifiable_type ',
        'notifiable_id ',
        'data ',
    ];

    public static function saveNotification($data) {
        $noti = new Notifications();
        $noti->notifiable_type = $data['notifiable_type'];
        $noti->type = $data['type'];
        $noti->notifiable_id = $data['notifiable_id'];
        $noti->data = $data['data'];
        $noti->save();
    }

}
