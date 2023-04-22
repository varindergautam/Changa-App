const express = require('express');
const app = express();
// const http = require('http');
const server = require('http').createServer(app);


const io = require('socket.io')(server, {
  cors : { origin: "*" ,reconnect: true, rejectUnauthorized : false}
});

app.get('/', function (req, res) {
    res.send('<h1>Socket is working</h1>');
});

io.on('connection', (socket) => {
    console.log('yes');
  console.log('connection', socket.id);

  socket.on('message', (message) => {
    console.log(message);
    io.emit('message', message);
});


  socket.on('disconnect', (socket) => {
    console.log('disconnect');
  })
});

server.listen(3000, () => {
  console.log('listening on *:3000');
});