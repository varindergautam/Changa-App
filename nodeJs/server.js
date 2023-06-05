const express = require("express");
const app = express();
const https = require('https');
const fs = require('fs');
const socketIO = require('socket.io');
const WebSocket = require('ws');

// Read SSL/TLS certificates
const privateKey = fs.readFileSync('../privateKey.pem', 'utf8');
const certificate = fs.readFileSync('../certificate.pem', 'utf8');
const credentials = { key: privateKey, cert: certificate };

// Create HTTPS server
const server = https.createServer(app);
const wss = new WebSocket.Server({ server});

// Initialize Socket.IO
// const io = socketIO(server);

const io = require("socket.io")(server, {
    cors: { origin: "*", reconnect: true, rejectUnauthorized: false },
});
app.get('/', (req, res) => {
    res.send('node working');
});

io.on("connection", (socket) => {
    console.log("connection", socket.id);
});

// Start the server
server.listen(3000, () => {
  console.log('io');
});
