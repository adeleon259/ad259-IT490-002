<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

$request = array(
    "type" => "login",
    "username" => $username,
    "password" => $password
);

$response = $client->send_request($request);

echo json_encode($response);
?>
