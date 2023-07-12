<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\AudioTag;
use App\Models\AudioTagMulti;
use App\Models\BeginTripe;
use App\Models\BeginTripMemo;
use App\Models\BeignTripNowFeel;
use App\Models\Felling;
use App\Models\Intention;
use App\Models\TripJournal;
use App\Models\UserNotificationSetting;
use App\Models\Visual;
use App\Models\Narrative;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class BeginTripController extends BaseController
{
    public function fellings() {
        try{
            $feelings = Felling::get();
            if($feelings->count() > 0) {
                return $this->sendResponse( $feelings, 'Success' );
            } else {
                return $this->sendError( [], 'No Data found');
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function intentions() {
        try{
            $feelings = Intention::get();
            if($feelings->count() > 0) {
                return $this->sendResponse( $feelings, 'Success' );
            } else {
                return $this->sendError( [], 'No Data found');
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function visual() {
        try{
            $feelings = Visual::get();
            if($feelings->count() > 0) {
                foreach($feelings as $key => $audio_tag) {
                    $feelings[$key]['file'] = !empty($audio_tag->file) ? asset('/storage/file/'. $audio_tag->file) : null;
                }
                return $this->sendResponse( $feelings, 'Success' );
            } else {
                return $this->sendError( [], 'No Data found');
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function audioTag() {
        try{
            $feelings = AudioTag::get();
            if($feelings->count() > 0) {
                return $this->sendResponse( $feelings, 'Success' );
            } else {
                return $this->sendError( [], 'No Data found');
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function audio(Request $request){
        $users = Audio::with('user', 'audioTagMulti')->get();
        if($request->id) {
            $users = Audio::where('id', $request->id)->with('audioTagMulti', 'user')->get();
        }

        if($request->tag_id) {
            $multi = AudioTagMulti::where('audio_tag_id', $request->tag_id)->get()->pluck('audio_id')->toArray();
            $users = Audio::with('audioTagMulti','user')->whereIn('id', $multi)->get();
        }

        if($users->count() > 0) {
            foreach($users as $key => $mediate) {
                $users[$key]['file'] = asset('/storage/file/'. $mediate->file);
                $users[$key]['user']['profile_pic'] = !empty($mediate->profile_pic) ? asset('/storage/profile_pic/'. $mediate->profile_pic) : null;

                $users[$key]['user']['background_image'] = !empty($mediate->background_image) ? asset('/storage/file/'. $mediate->background_image) : null;

                $arr = [];
                foreach($mediate->audioTagMulti as $tag) {
                    $arr[] = AudioTag::where('id', $tag->audio_tag_id)->get()->toArray();
                }
                $users[$key]['audio_tag'] = $arr;
            }
            return $this->sendResponse( $users, 'Success' );
        } else {
            return $this->sendError( [], 'No Data found');
        }
    }

    public function tripJournal() {
        try{
            $feelings = TripJournal::get();
            if($feelings->count() > 0) {
                return $this->sendResponse( $feelings, 'Success' );
            } else {
                return $this->sendError( [], 'No Data found');
            }
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request) {
        try {
            $userID = auth('sanctum')->user()->id;
            $beginTripArr = [
                'user_id' => $userID,
                'feeling_id' => $request->feeling_id,
                'feeling_text' => $request->feeling_text,
                'intention_id' => $request->intention_id,
                'intention_text' => $request->intention_text,
                'type_of_trip' => $request->type_of_trip,
                'satisfaction_type' => $request->satisfaction_type,
                'satisfaction_text' => $request->satisfaction_text,
                'visual_id' => $request->visual_id,
                'audio_tag_id' => $request->audio_tag_id,
                'audio_id' => $request->audio_id,
                'trip_start_date' => Carbon::now()->toDateTimeString(),
                'trip_icon' => $request->trip_icon,
                'day' => $request->day,
            ];

            if($request->trip_end != 'end') {
                $beginTrip = BeginTripe::create($beginTripArr);

                if($request->file('voice_memo')) {
                    $profile = $request->file('voice_memo');
                    $path = "file";
                    $fileName = ChangaAppHelper::uploadfile($profile, $path);
                    $fileType = ChangaAppHelper::checkFileExtension($fileName);
                }
    
                $memoArr = [
                    'begin_tripe_id' => $beginTrip->id,
                    'voice_memo' => @$fileName,
                    'file_type' => $request->file_type,
                    'guide_id' => $request->guide_id,
                    'time_of_recording' => $request->time_of_recording_memo,
                ];
    
                $beginTripMemo = BeginTripMemo::updateOrCreate(['id' => $request->begin_trip_memos_id],
                    $memoArr
                );
    
                $beginTrip->begin_trip_memo = $beginTripMemo;
            }
            

            if(!empty($request->begin_trip_id) && $request->trip_end == 'end') {
                $pushNotificationData['message'] = 'The trip has been ended';
                $pushNotificationData['id'] = $request->begin_trip_id;
                $pushNotificationData['notifiable_type'] = 'trip_end';
                $beginTrip = BeginTripe::updateOrCreate(['id' => $request->begin_trip_id],
                    [   
                        'trip_end_date' => Carbon::now()->toDateTimeString(),
                        'time_of_recording' => $request->time_of_recording,
                    ]
                );
                $setting = UserNotificationSetting::where('user_id', $userID)->first();
                if(isset($setting) && $setting->end_trip_remainder == '1') {
                    ChangaAppHelper::sendNotication($userID, $pushNotificationData);
                } 
            }

            
            return $this->sendResponse( $beginTrip, 'Success' );
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function feelingNow(Request $request) {
        try {
            $validator = Validator::make($request->all(), self::feelingNowRule($request));
            if ($validator->fails()) {
                return $this->sendError( $validator->errors(), [] );
            }
            $beginTripArr = [
                'beign_trip_id' => $request->beign_trip_id,
                'feeling_id' => $request->feeling_id,
                'feeling_text' => $request->feeling_text,
                'refelct' => $request->refelct,
                'trip_journal_emotion' => $request->trip_journal_emotion,
                'trip_journal_emotion_text' => $request->trip_journal_emotion_text,
                'trip_journal_insiahts' => $request->trip_journal_insiahts,
                'trip_journal_insiahts_text' => $request->trip_journal_insiahts_text,
                'trip_journal_vision' => $request->trip_journal_vision,
                'trip_journal_vision_text' => $request->trip_journal_vision_text,
                'trip_journal_unaudied' => $request->trip_journal_unaudied,
                'trip_journal_unaudied_text' => $request->trip_journal_unaudied_text,
                'different_tommorow' => $request->different_tommorow,
            ];

            $beginTrip = BeignTripNowFeel::updateOrCreate(['id' => $request->id],
                $beginTripArr
            );

            return $this->sendResponse( $beginTrip, 'Success' );
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function feelingNowRule() {
        return [
            'beign_trip_id' => 'required',
        ];
    }

    public function deleteRule() {
        return [
            'id' => 'required',
        ];
    }

    public function destroy(Request $request) {
        try {
            $validator = Validator::make($request->all(), self::deleteRule($request));
            if ($validator->fails()) {
                return $this->sendError( $validator->errors(), [] );
            }
            if(!BeginTripe::find($request->id)) {
                return $this->sendError('not found', [] );
            }

            if(BeginTripe::where('id', $request->id)->delete()) {
                BeginTripMemo::where('begin_tripe_id',$request->id)->delete();
                BeignTripNowFeel::where('beign_trip_id',$request->id)->delete();
                return $this->sendResponse( [], 'Success' );
            }
        }
        catch (\Exception $ex) {
            return $this->sendError( $ex->getMessage(), 'something went wrong');
        }
    }

    public function index() {
        try {
            $beginTripes = BeginTripe::with('beginTripMemo', 'beginTripNowFeel')->where('user_id', auth('sanctum')->user()->id)->get();
            return $this->sendResponse( $beginTripes, 'Success' );
        }
        catch (\Exception $ex) {
            return $this->sendError( $ex->getMessage(), 'something went wrong');
        }
    }

    public function history() {
        try {
            $beginTrips = BeginTripe::withCount('beginTripMemo')->with('beginTripNowFeel');
            $begin_trips = $beginTrips->where('user_id', auth('sanctum')->user()->id)->get();
            // print_r($begin_trips->toArray());die;
            if($begin_trips) {
                $memo_sum = 0;
                $time_record_trip = 0;
                $total_journal = 0;
                $success = [];
                foreach($begin_trips as $trips) {
                    $success['total_trip'] = count($begin_trips);
                    $success['total_memo'] = $memo_sum = $trips->begin_trip_memo_count + $memo_sum;
                    $success['time_record_trip'] = $time_record_trip = $trips->time_of_recording + $time_record_trip;
                    $trip_journal_emotion_text = !empty($trips->beginTripNowFeel->trip_journal_emotion_text) ? str_word_count($trips->beginTripNowFeel->trip_journal_emotion_text) : 0;
                    $trip_journal_insiahts_text = !empty($trips->beginTripNowFeel) ? str_word_count($trips->beginTripNowFeel->trip_journal_insiahts_text) : 0;
                    $trip_journal_vision_text = !empty($trips->beginTripNowFeel) ? str_word_count($trips->beginTripNowFeel->trip_journal_vision_text) : 0;
                    $trip_journal_unaudied_text = !empty($trips->beginTripNowFeel) ? str_word_count($trips->beginTripNowFeel->trip_journal_unaudied_text) : 0;
                    $success['word_journal'] = $total_journal = $trip_journal_emotion_text +$trip_journal_insiahts_text + $trip_journal_vision_text + $trip_journal_unaudied_text + $total_journal;
                    $success['narrative'] = Narrative::where('user_id', auth()->user()->id)->count();
                }
            }
            return $this->sendResponse( $success, 'Success' );
        } catch (\Throwable $th) {
            return $this->sendError( $th->getMessage(), 'something went wrong');
        }
    }
}
