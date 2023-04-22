<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i"
          rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .border-yellow:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: #e4ac01;
        }

        .border-yellow:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: #e5e5e5;
        }
    </style>
</head>
<body style="background:#f0f1f3">
<div style="max-width:600px;margin:0 auto;background:#fff;">

    <div style="width:100%;box-shadow:0px 0px 9px #b7b0b0;text-align:center;">
        <div style="width:100%;background:#b1c252;padding:10px 0px;">
            <img src="{{ url('public/front/images/logo.png') }}" style="max-width:150px;">
        </div>
        <div style="width:100%; text-align:center;padding:10px 20px;">
            <h4 style="margin: 10px 0px 20px;font-size: 24px;font-weight: 400;font-style: oblique;">
                Dear {{ $name }}</h4>
            <h2 style="margin-top:30px;margin-bottom:0px;font-size:30px;font-style: oblique;color:#063979;font-weight: 500;">
                @if($mail_type == 'forgot')

                @else
                Your account has been created.
                @endif
            </h2>
            <p style="font-size:22px;font-style:oblique;color:#847f7f;font-weight: 300;margin-bottom:0px;text-align:center">Welcome to
                the Changa App.</p>
                <p style="font-size:22px;font-style:oblique;color:#847f7f;font-weight: 300;margin-bottom:0px;text-align:center">Click to verify your account and use below login details <br>
                {{$verify_url}}</p>
                {{-- <p style="font-size:22px;font-style:oblique;color:#847f7f;font-weight: 300;margin-bottom:40px;">From now on, please log in to your account using following Detail</p>
            <div style="width:100%;max-width:400px;margin:0 auto 40px;display:inline-block;padding:20px;border:5px solid #ccc;"> --}}
                <p style="margin-bottom:0px;">
                    <label style="text-align:left;width:90px;font-weight:600;">Email:</label>
                    <a href="#" style="font-weight:600; color:#2794f9">{{ $email }}</a>
                </p>
                <p style="margin-top:10px;">
                    <label style="text-align:left;width:90px;font-weight:600;">Password:</label>
                    <span style="font-weight:600;">{{ $password }}</span>
                </p>
            </div>
        </div>
        <div style="float:left;width:100%; padding:3px 10px;background:#b1c252;text-align:center;">
            <p style="font-size:13px;"><span style="color:#fdfdfd;font-weight:300;">Powered By: </span><span style="font-weight:500;color:#fff;">Changa App</span></p>
        </div>
    </div>
</div>
</body>
</html>
