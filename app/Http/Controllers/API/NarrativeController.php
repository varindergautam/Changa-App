<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Narrative;
use Illuminate\Http\Request;
use Validator;

class NarrativeController extends BaseController
{
    public function index() {
        $mediate_tags = Narrative::where('user_id', auth()->user()->id)->get();
        if($mediate_tags->count() > 0) {
            return $this->sendResponse( $mediate_tags, 'Success' );
        } else {
            return $this->sendError( [], 'No Data found');
        }
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'narrative' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors()->all());
            }
            $narrative = new Narrative();
            if($request->id) {
                $narrative = Narrative::find($request->id);
                if(empty($narrative)) {
                    return $this->sendError( [], 'No Data found');
                }
            }
            $narrative->narrative = $request->narrative;
            $narrative->user_id = auth()->user()->id;
            $narrative->save();
            return $this->sendResponse( $narrative, 'Success' );
        } catch (\Throwable $th) {
            return $this->sendError( $th->getMessage(), 'something went wrong');
        }
    }

    public function delete(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors()->all());
            }
            $narrative = Narrative::find($request->id);
            if(!$narrative) {
                return $this->sendResponse('', 'No found' );
            }
            $narrative->delete();
            return $this->sendResponse( null, 'Success' );
        } catch (\Throwable $th) {
            return $this->sendError( $th->getMessage(), 'something went wrong');
        }
    }
}
