// needs node.js
// npm socket.io
// npm amqp
// rabbitmq running at localhost

var http = require('http'),
    url = require('url'),
    fs = require('fs'),
    io = require('socket.io'),
    amqp = require('amqp'),
    sys = require(process.binding('natives').util ? 'util' : 'sys'),
    connect = require('connect');

connect().use(connect.static('public')).listen(3000);

send404 = function(res, err) {
  res.writeHead(404);
  res.write('404'+err);
  res.end();
};

server = http.createServer(function(req, res) {
  // your normal server code
  var path = url.parse(req.url).pathname;
  switch (path) {
  //case '/json.js':
  case '/':
  fs.readFile(__dirname + "/index.html", function(err, data) {
    if (err) return send404(res, err);
    res.writeHead(200, {'Content-Type': path == 'json.js' ? 'text/javascript' : 'text/html'})
    res.write(data, 'utf8');
    res.end();
  });
  break;
  }
});

server.listen(8090);

// socket.io
var socket = io.listen(server);
// ampq
var connection = amqp.createConnection( { host: '127.0.0.1' } );
var messages = [];
var client1;

connection.on('ready', function() {
  var exchange = connection.exchange('newMapExchange', {type: 'topic', passive: false, durable: true, autoDelete: false}, onExchangeOpen);
  socket.on('connection', function(client) {
    client1 = client;
	  client.on('message', function(message) {
	  });
	  client.on('disconnect', function() {
      console.log('Server has disconnected.');
    });
  });
});

function onExchangeOpen() {
  var q = connection.queue('mapQueue', {durable: true, exclusive: false, autoDelete: false}, function(queue) {
    queue.subscribe(function(message) {
      msg = message.data.toString();
      if (client1) { console.log('Msg => '+msg); client1.send(msg); }
      else {console.log('no client');}
    });
    queue.bind("newMapExchange", "key.a");
  });
  q.on('queueBindOk', function() { onQueueBindOk() });
}

function onQueueBindOk() {

}
