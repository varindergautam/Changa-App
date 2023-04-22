<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends BaseController
{
    function groupList() {
        $group  = Group::with('lastMessage')->get();
        if($group->count() > 0) {
            return $this->sendResponse( $group, 'Success' );
        } else {
            return $this->sendError("No group found");
        }
    }
    
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }
        
        $userID = auth('sanctum')->user()->id;
        $groupId = $request->group_id;

        $groupUser = GroupUser::where('group_id', $groupId)->first();
        
        $explodeGroupUser = explode(',', $groupUser->user_id);
        $intersect = array_diff($explodeGroupUser, [$userID]);
        $users = array_values($intersect);
        
        $message = new Message();
        $message->group_id = $groupId;
        $message->user_id = $userID;
        $message->message = $request->message;
        $message->reciever_id = implode(',', $users);
        $message->save();
        return $this->sendResponse( $message, 'Sucess' );
    }

    public function recieve(Request $request) {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors()->all());
        }
        $userID = auth('sanctum')->user()->id;
        $groupId = $request->group_id;

        $message = Message::with('user:id,first_name')
        // where('user_id', $userID)
        ->
        where('group_id', $groupId)
        ->get()->toArray();
        return $this->sendResponse( $message, 'Sucess' );
    }
}
