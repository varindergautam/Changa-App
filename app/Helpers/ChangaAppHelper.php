<?php
namespace App\Helpers;

use App\Models\User;
use App\Models\UserDeviceToken;
use Carbon\Carbon;

class ChangaAppHelper 
{
    public static function sendAjaxResponse($valid, $message, $redirect = "", $data = [], $validations = [])
    {
        if (!$valid) {
            // parameter is an exception object
            $message = $message->getMessage();
        }
        $response = [
            'valid' => $valid,
            'error' => $message,
            'data' => $data,
            'redirect' => $redirect,
            'validations' => $validations
        ];
        return response()->json($response);
    }

    public static function generate_random_number($digits = 8) {
        srand((double) microtime() * 10000000);
        $input = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
        $random_generator = "";
        for ($i = 1; $i <= $digits; $i++) {
            if (rand(1, 2) == 1) {
                $rand_index = array_rand($input);
                $random_generator .=$input[$rand_index];
            } else {
                $random_generator .=rand(1, 9);
            }
        }
        return $random_generator;
    }

    public static function uploadfile($file, $path) {
        $fileName = md5((string)\Str::uuid()). '.' . $file->getClientOriginalExtension();
        $createFolder = storage_path("app/public/" .$path);
        // print_r($createFolder);die;
        if (! \File::exists($createFolder)) {
            \File::makeDirectory($createFolder, 0777, true);
        }
        $file->move($createFolder, $fileName);

        return $fileName;
    }

    public static function checkFileExtension ($filename) {
        $fileType = '';
        $fileExtension = \File::extension($filename);
        if($fileExtension == 'png' || $fileExtension == 'jpg' || $fileExtension == 'gif' || $fileExtension == 'jpeg' || $fileExtension == 'tif' || $fileExtension == 'tiff' || $fileExtension == 'webp') {
            $fileType = config('fileType.image');
        }
        else if($fileExtension == 'mov' || $fileExtension == 'mp4' || $fileExtension == 'AVI' || $fileExtension == 'WMV' || $fileExtension == 'MKV' || $fileExtension == 'MPEG-2' || $fileExtension == 'FLV') {
            $fileType = config('fileType.video');
        } else if($fileExtension == 'mp3'){
            $fileType = config('fileType.audio');
        }
        return $fileType;
    }


    //Send Notification
    public static function sendNotication($notifcationUserId, $data)
    {
        $user = UserDeviceToken::where('user_id', $notifcationUserId)->first();
        if ($user && !is_null($user->device_token) && !is_null($user->device_type)) {

            $device_token = $user->device_token;

            if ($user->device_type == config('deviceType.android'))
            {
                 $fcmMsg = array(
                     'body' => $data['message'],
                     'title' => $data['message'],
                     // 'largeIcon' => 'large_icon',
                     // 'smallIcon' => 'small_icon',
                     // 'sound' => 'default',
                     // 'badge' => 1,
                     // 'notification_type' => $data['notification_type'],
                     // 'id' => $data['id'],
                 );

                 $extraData = ['notification_type' => $data['notification_type'],
                     'id' => $data['id']];

                 $fcmFields = ['to' => $device_token, 'notification' => $fcmMsg, 'data' => $extraData];

                 $headers = array(
                     'Authorization: key=' . config('deviceType.server_key'),
                     'Content-Type: application/json'
                 );

                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                 curl_setopt($ch, CURLOPT_POST, true);
                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));

                 $result = curl_exec($ch);
                 \Log::info('Remainder Notification ANDROID.');
                // print_r($result);die('and');
                 curl_close($ch);
            }
            else
            {
                if ($user->device_type == config('deviceType.ios'))
                {

                    $fcmMsg = array(
                     'body' => $data['message'],
                     'title' => $data['message'],
                     // 'largeIcon' => 'large_icon',
                     // 'smallIcon' => 'small_icon',
                     // 'sound' => 'default',
                     // 'badge' => 1,
                     // 'notification_type' => $data['notification_type'],
                     // 'remainder_id' => $data['remainder_id'],
                 );

                 $extraData = ['notification_type' => $data['notification_type'],
                     'remainder_id' => $data['remainder_id']];

                 $fcmFields = ['to' => $device_token, 'notification' => $fcmMsg, 'data' => $extraData];

                    $headers = array(
                        'Content-Type: application/json',
                        'Authorization: key=' . config('deviceType.server_key'),
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));

                    $result = curl_exec($ch);

                    \Log::info('Remainder Notification IOS.');
                    // print_r($result);die('ios');
                    curl_close($ch);
                }
            }
        }
    }

    public static function dateFormat($date) {
        return Carbon::parse($date)->format('d M, Y');
    }
}