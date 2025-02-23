<?php
require_once('rabbitMQLib.inc');

$username = $_POST['username'];
$password = $_POST['password'];

$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
$response = $client->send_request($request);

if ($response['returnCode'] == "success") {
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['session_token'] = $response['session_token'];
    echo "Login successful!";
} else {
    echo "Login failed: " . $response['message'];
}
?>
