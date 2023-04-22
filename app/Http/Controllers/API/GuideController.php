<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\GuideCategory;
use Illuminate\Http\Request;

class GuideController extends BaseController
{
    public function category() {
        try {
            $mediate_tags = GuideCategory::get();
            if($mediate_tags->count() > 0) {
                foreach($mediate_tags as $key => $mediate_tag) {
                    $mediate_tags[$key]['file'] = !empty($mediate_tag->file) ? asset('/storage/file/'. $mediate_tag->file) : null;
                }
                $success[ 'data' ] = $mediate_tags;
                return $this->sendResponse( $success, 'Success' );
            } else {
                return $this->sendResponse( [], 'No Data found');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function index() {
        try {
            $mediate_tags = Guide::with('guideCategory', 'user')->get();
            if($mediate_tags->count() > 0) {
                foreach($mediate_tags as $key => $mediate_tag) {
                    $mediate_tags[$key]['file'] = !empty($mediate_tag->file) ? asset('/storage/file/'. $mediate_tag->file) : null;
                    $mediate_tags[$key]['guideCategory']['file'] = !empty($mediate_tag->guideCategory->file) ? asset('/storage/file/'. $mediate_tag->guideCategory->file) : null;
                }
                $success[ 'data' ] = $mediate_tags;
                return $this->sendResponse( $success, 'Success' );
            } else {
                return $this->sendResponse( [], 'No Data found');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
