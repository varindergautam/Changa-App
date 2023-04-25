<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatTestController extends Controller
{
    public function chat($group_id) {
        $data['group_id'] = $group_id;
        return view('chat.chat', $data);
    }
}
