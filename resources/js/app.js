import './bootstrap';

// app.js
import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;

// Initialize Laravel Echo
window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: window.location.hostname + ':3000',
});

// Listen for new chat messages
window.Echo.channel('channel-name')
  .listen('.chat.message', (data) => {
    console.log(data.user.name + ': ' + data.message);
  });
