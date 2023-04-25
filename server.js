const express = require("express");
const app = express();
// const http = require('http');
const server = require("http").createServer(app);
var mysql = require("mysql");

var con = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "",
    database: "apk_changa_app",
});

con.connect(function (err) {
    if (err) throw err;
});

const io = require("socket.io")(server, {
    cors: { origin: "*", reconnect: true, rejectUnauthorized: false },
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

    socket.on("disconnect", (socket) => {
        console.log("disconnect");
    });
});

server.listen(3000, () => {
    console.log("listening on *:3000");
});
