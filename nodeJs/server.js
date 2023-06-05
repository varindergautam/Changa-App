
//text/x-generic server.js ( ASCII C++ program text, with CRLF line terminators )
const express = require("express");
const app = express();
const ws = new WebSocket("wss://socket.changaapp.com/");

const server = require("http").createServer(app);
var mysql = require("mysql2");

const cors = require('cors');

const corsOptions = {
    origin: '*', // or passing the * for allow all
    credentials: false,
    optionSuccessStatus: 200
}
app.use(cors(corsOptions));

var con = mysql.createConnection({
    host: '127.0.0.1',
    user: 'changa_app',
    password: 'changaapp@6633',
    database: 'changa_app',
    port: 3306,
});

con.connect(function (err) {
    if (err) throw err;
});

app.get('/', function(req, res)
{
	res.send('<h1>Socket is working</h1>');
});

/*const io = require("socket.io")(server, {
    cors: { origin: "*", reconnect: true, rejectUnauthorized: false },
});*/
const { Server } = require("socket.io");

const io = new Server(server, {
    cors: {
        origin: process.env.NODE_LINK,
    }
});

io.on("connection", (socket) => {
    console.log("connection", socket.id);

    socket.on("create-room", (roomId) => {


        console.log(roomId + " -- group id");
        io.emit("room-created", roomId);
    });

    socket.on("join", (room_id, user_id) => {
        console.log(room_id + " -- join");
        socket.join(room_id);
    });

    socket.on("typing", (data) => {
        if (data.typing == true) io.emit("display", data);
        else io.emit("display", data);
    });


    //lisen from client
    socket.on("sendChatToServer", function (data) {
           console.log("data "+ data);
        var today = new Date();
        var date =
            today.getFullYear() +
            "-" +
            (today.getMonth() + 1) +
            "-" +
            today.getDate();
        var time =
            today.getHours() +
            ":" +
            today.getMinutes() +
            ":" +
            today.getSeconds();
        var dateTime = date + " " + time;

        var insertMessage =
            "insert into messages (group_id, user_id, message, created_at, updated_at) values (" +
            data.receiver +
            "," +
            data.sender +
            ",'" +
            data.message +
            "','" +
            dateTime +
            "','" +
            dateTime +
            "')";
        console.log(insertMessage);
        con.query(insertMessage, function (err, result) {
            if (err) throw err;
            console.log(result.affectedRows + " record(s) insert");
        });

        io.to(data.receiver).emit("sendChatToClient", data);
    });



socket.on('sendChatToClient', function(data) {
console.log(data);
            $('.last_message_' + data.sender).html(data.message);
            var html = '';
                html +=
                    '<div class="mt-3 col-lg-12 bg-chat p-3 br-12 text-15">' + data.username + ': ' + data.message +
                    '</div>';
                document.getElementById('chat-box').innerHTML += html;
        });

    socket.on("disconnect", (socket) => {
        console.log("disconnect");
    });
});

// server.listen(3000, () => {
//     console.log("listening on *:3000");
// });

server.listen();
