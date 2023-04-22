<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\Therapy;
use App\Models\TherapyTag;
use App\Models\TherapyTagMulti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TherapyController extends BaseController
{
    public function therapyTag() {
        $mediate_tags = TherapyTag::get();
        if($mediate_tags->count() > 0) {
            $success[ 'data' ] = $mediate_tags;
            return $this->sendResponse( $success, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function therapy(Request $request) {
        $users = Therapy::with('therapyTagMulti', 'user', 'favourite')->get();

        if($request->id) {
            $users = Therapy::where('id', $request->id)->with('therapyTagMulti', 'user', 'favourite')->get();
        }

        if($request->tag_id) {
            $multi = TherapyTagMulti::where('therapy_id', $request->tag_id)->get()->pluck('therapy_id')->toArray();
            $users = Therapy::with('therapyTagMulti','user', 'favourite')->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $mediate) {
                $description = strip_tags(html_entity_decode($mediate->description));
                $users[$key]['description'] = strip_tags(nl2br($description));
                $users[$key]['file'] = asset('/storage/file/'. $mediate->file);
                $users[$key]['user']['profile_pic'] = !empty($mediate->profile_pic) ? asset('/storage/profile_pic/'. $mediate->profile_pic) : null;

                $users[$key]['user']['background_image'] = !empty($mediate->background_image) ? asset('/storage/file/'. $mediate->background_image) : null;

                $arr = [];
                foreach($mediate->therapyTagMulti as $tag) {
                    $arr[] = TherapyTag::where('id', $tag->therapy_tag_id)->get()->toArray();
                }
                $users[$key]['therapy_tag'] = $arr;
            }
            $success[ 'data' ] = $users;
            return $this->sendResponse( $success, 'Success' );
        } else {
            return $this->sendResponse( [], 'No Data found');
        }
    }

    public function store(Request $request) {
        try {
            if($request->id && !Therapy::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                return $this->sendError( $validator->errors(), [] );
            }

            if($request->file('file')) {
                $profile = $request->file('file');
                $path = "file";
                $fileName = ChangaAppHelper::uploadfile($profile, $path);
                $fileType = ChangaAppHelper::checkFileExtension($fileName);
            } else {
                $fileName = $request->check_file;
                $fileType = $request->file_type;
            }

            if($request->file('background_image')) {
                $profile = $request->file('background_image');
                $path = "file";
                $background_image = ChangaAppHelper::uploadfile($profile, $path);
            } else {
                $background_image = $request->background_image;
            }

            $Learn = Therapy::updateOrCreate(['id' => $request->id],
                [
                    // 'therapy_tag_id' => $request->tag,
                    'user_id' => auth()->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'file' => $fileName,
                    'file_type' => $fileType,
                    'background_image' => $background_image,
                ]
            );

            if(isset($request->tag)) {
                TherapyTagMulti::where('therapy_id', $Learn->id)->delete();
                foreach($request->tag as $tag) {
                    $mutli  = new TherapyTagMulti();
                    $mutli->therapy_tag_id = $tag;
                    $mutli->therapy_id = $Learn->id;
                    $mutli->save();
                }
            }


            $Learn->file = asset('/storage/file/'. $Learn->file);
            $Learn->background_image = asset('/storage/file/'. $Learn->background_image);

            return $this->sendResponse( $Learn, 'Success' );

        } catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }

    public function validationForStore($request)
    {
        $file = 'required|mimetypes:image/jpeg,image/png,image/gif,video/webm,video/mp4,audio/mp3';
        return [
            'tag' => 'required',
            'title' => 'required|unique:therapies,title,'. $request->id,
            'description' => 'required',
            'file' => $file,
            'background_image' => 'required|mimetypes:image/jpeg,image/png,image/jpg',
        ];
    }

    public function destroy(Request $request) {
        try {
            if(!Therapy::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            if(Therapy::where('id', $request->id)->delete()) {
                TherapyTagMulti::where('therapy_id',$request->id)->delete();
                return $this->sendResponse( [], 'Success' );
            }
        }
        catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }
}

