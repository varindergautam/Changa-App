// server.js
const express = require('express');
const http = require('http');
const socketIO = require('socket.io');

var mysql2 = require("mysql2");
require('dotenv').config();

const app = express();
const server = http.createServer(app);
const io = socketIO(server, {
  cors: {
    origin: '*', // Replace with your client's URL
    methods: ['GET', 'POST'],
    allowedHeaders: ['Content-Type'],
  },
});

var con = mysql2.createConnection({
    host: "localhost",
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
});

con.connect(function (err) {
    if (err) throw err;
});

app.get('/', (req, res) => {
    res.send('node working');
});

// Socket.IO connection handler
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

// Start the server
const port = process.env.SOCKET_PORT;
server.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
