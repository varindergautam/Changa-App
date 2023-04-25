<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <input type="hidden1" name="senderId" id="senderId" value="{{ Auth::user()->id }}">
    <input type="hidden1" name="recieverId" id="recieverId" value="{{ isset($group_id) ? $group_id : '' }}">
    <input type="hidden1" name="" id="username" value="{{ Auth::user()->first_name }}">

    <div id="chat-box"></div>

    <div class="box-footer px-5">
        <div class="form-floating">
            <textarea class="form-control message" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
            {{-- <label for="floatingTextarea2">Write Message</label> --}}
            <span class="description-error error" style="display: none;">Message can not be Empty</span>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn--primary mt-3" id="sendMessage">Send
                Message</button>&nbsp;&nbsp;&nbsp;
            <a href="{{ url()->previous() }}" class="btn btn--primary mt-3">Back</a>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdn.socket.io/4.1.1/socket.io.min.js"
        integrity="sha384-cdrFIqe3RasCMNE0jeFG9xJHog/tgOVC1E9Lzve8LQN1g5WUHo0Kvk1mawWjxX7a" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        let ip_address = 'http://localhost';
        let socket_port = 3000;
        var socketId = '';
        let socket = io(ip_address + ':' + socket_port);
        socket.on('connect', () => {
            socketId = socket.id;
            console.log(socketId + 'socketId');
        });

        let username = $('#username').val();
        let recieverId = $("#recieverId").val();
        let senderId = $("#senderId").val();

        socket.emit('create-room', recieverId);

        socket.emit('join',  recieverId, senderId );

        var timeout;

        function timeoutFunction() {
            typing = false;
            socket.emit("typing", false);
        }

        $('.message').keyup(function() {
            typing = true;
            socket.emit('typing', 'typing...');
            clearTimeout(timeout);
            timeout = setTimeout(timeoutFunction, 2000);
        });

        socket.on('display', function(data) {
            if (data) {
                $('.message').html(data);
            } else {
                $('.message').html("");
            }
        });

        $('#sendMessage').click(function(e) {
            let message = $('.message').val();
            socket.emit('sendChatToServer', {
                message: message,
                username: username,
                sender: senderId,
                receiver: recieverId,
            });

            var html = '';

            // html +=
            //     '<div class="mt-3 col-lg-12 bg-chat p-3 br-12 text-15">' + username + ': ' + message +
            //     '</div>';

            // document.getElementById('chat-box').innerHTML += html;

            $('.message').val('');
        });

        socket.emit('adduser', senderId);

        socket.on('sendChatToClient', function(data) {
            $('.last_message_' + data.sender).html(data.message);
            var html = '';
                html +=
                    '<div class="mt-3 col-lg-12 bg-chat p-3 br-12 text-15">' + data.username + ': ' + data.message +
                    '</div>';
                document.getElementById('chat-box').innerHTML += html;
        });
    </script>
</body>

</html>
