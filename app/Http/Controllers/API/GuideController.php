<?php

namespace App\Http\Controllers\API;

use App\Helpers\ChangaAppHelper;
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
                return $this->sendResponse( $mediate_tags, 'Success' );
            } else {
                return $this->sendResponse( [], 'No Data found');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function index(Request $request) {
        try {
            $mediate_tags = Guide::with('guideCategory', 'user')->get();
            if($request->category_id) {
                $mediate_tags = Guide::with('guideCategory', 'user')
                ->whereHas('guideCategory', function($query) use ($request){
                    $query->where('id', $request->category_id);
                })
                ->get();
            }
            if($mediate_tags->count() > 0) {
                foreach($mediate_tags as $key => $mediate_tag) {
                    $mediate_tags[$key]['created_date'] = ChangaAppHelper::dateFormat($mediate_tag->created_at);
                    $mediate_tags[$key]['file'] = !empty($mediate_tag->file) ? asset('/storage/file/'. $mediate_tag->file) : null;
                    $mediate_tags[$key]['guideCategory']['file'] = !empty($mediate_tag->guideCategory->file) ? asset('/storage/file/'. $mediate_tag->guideCategory->file) : null;
                }
                return $this->sendResponse( $mediate_tags, 'Success' );
            } else {
                return $this->sendResponse( [], 'No Data found');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
