<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if (count($argv) < 2) {
    die('Enter a file name for routes.');
}
$fileName = $argv[1];

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('newMapExchange', 'topic', false, true, false);
$channel->queue_declare('mapQueue', false, true, false, false);
$channel->queue_bind('mapQueue', 'newMapExchange');
$routingKey = 'key.a';
$handle = @fopen('location_files/' . $fileName, "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        echo $buffer;
        $message = explode(',', $buffer);
        $json = sprintf(
                    '{"id":"%s", "lat":%s, "lng":%s, "time":"%s"}',
                    $fileName, $message[0], $message[1], strftime('%Y-%m-%d %T', time())
        );
        $msg = new AMQPMessage($json);

        $channel->basic_publish($msg, 'newMapExchange', $routingKey);

        echo " [x] Sent :",$json," \n";
        sleep(10);
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

$channel->close();
$connection->close();
