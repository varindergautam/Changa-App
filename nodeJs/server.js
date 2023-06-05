const https = require('https');
const fs = require('fs');
const socketIO = require('socket.io');

// Read SSL/TLS certificates
const privateKey = fs.readFileSync('../privateKey.pem', 'utf8');
const certificate = fs.readFileSync('../certificate.pem', 'utf8');
const credentials = { key: privateKey, cert: certificate };

// Create HTTPS server
const server = https.createServer(credentials);

// Initialize Socket.IO
const io = socketIO(server);

// Socket.IO event handling
io.on('connection', (socket) => {
  console.log('A user connected');

  // Handle socket events
  socket.on('message', (data) => {
    console.log('Received message:', data);
    // Handle the message
  });

  socket.on('disconnect', () => {
    console.log('A user disconnected');
    // Handle disconnection
  });
});

// Start the server
server.listen(443, () => {
  console.log('Server listening on port 443');
});
