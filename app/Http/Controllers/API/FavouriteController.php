<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends BaseController
{
    public function markFavourite(Request $request) {
        try {
            if($request->id && !Favourite::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                return $this->sendError( $validator->errors(), [] );
            }

            $favourite = $request->favourite == config('commonStatus.ACTIVE') ? config('commonStatus.ACTIVE') : config('commonStatus.INACTIVE');

            $Learn = Favourite::updateOrCreate([
                'community_type' => $request->community_type, 
                'community_id' => $request->community_id
            ],
                [
                    'community_type' => $request->community_type,
                    'community_id' => $request->community_id,
                    'favourite' => $favourite,
                ]
            );

            return $this->sendResponse( $Learn, 'Success' );

        } catch (\Exception $ex) {
            return $this->sendResponse( $ex->getMessage(), 'something went wrong');
        }
    }

    public function validationForStore($request)
    {
        return [
            'community_type' => 'required',
            'community_id' => 'required',
            'favourite' => 'required',
        ];
    }
}
