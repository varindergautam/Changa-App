<?php

namespace App\Http\Controllers\web;

use App\Helpers\ChangaAppHelper;
use App\Http\Controllers\Controller;
use App\Models\TripJournal;
use Illuminate\Http\Request;
use Exception;
use Session;
use Validator;

class TripJournalController extends Controller
{
    public function index()
    {
        try{
            $users = TripJournal::paginate(env('PAGINATE'));
            return view('admin.trip_journal.list')->with('users',$users);
            }catch(\Throwable $e){
            return  $e;
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trip_journal.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = TripJournal::find($id);
        return view('admin.trip_journal.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $user = TripJournal::where('id',$id)->first();
            
            return view('admin.trip_journal.edit', compact('user'));
        } catch(\Throwable $e){
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $validations = [];
            $valid = false;
            $validator = Validator::make($request->all(), self::validationForStore($request));
            if ($validator->fails()) {
                $validations = $validator->errors();
                throw new \Exception("Please correct all the validations.");
            }

            $audio = TripJournal::updateOrCreate(['id' => $request->id],
                [
                    'name' => $request->title,
                    'text_1' => $request->text_1,
                    'text_2' => $request->text_2,
                ]
            );

            $valid = true;
            $message = "Created successfully";
            if($request->id) {
                $message = "Updated successfully";
            }
            $redirect = route('trip_journal');

        } catch (\Exception $ex) {
            $valid = false;
            $message = $ex;
            $redirect = '';
        }
        return ChangaAppHelper::sendAjaxResponse($valid, $message, $redirect, '', $validations);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {

            if (!$id) {
                throw new Exception('not found', Config('HttpCodes.fail'));
            }

            $result['status'] = 1;
            TripJournal::where('id', $id)->delete();
            Session::flash('success', 'Deleted Successfully.');
            return response()->json($result, 200);

        } catch (Exception $ex) {
            $result['status'] = 1;
            Session::flash('success', $ex->getMessage());

        }
        return response()->json($result, 200);
    }

    public function status($id, $status)
    {
        $userstatus = TripJournal::find($id);

        if ($userstatus) {
            $userstatus->active = ($status == '0') ? '1' : '0';
            $userstatus->save();

            $result['status'] = '1';
            return response()->json($result, 200);
        } else {
            $result['status'] = '0';
            return response()->json($result, 200);
        }
    }

    public function validationForStore($request)
    {
        return [
            'title' => 'required|unique:audios,title,'. $request->id,
        ];
    }
}
