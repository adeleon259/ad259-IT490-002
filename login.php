<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

if (!isset($_POST["type"]) || $_POST["type"] !== "login") {
    echo json_encode(["error" => "Invalid request type"]);
    exit();
}

// Capture username and password from the frontend
$username = $_POST["uname"];
$password = $_POST["pword"];

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;

$response = $client->send_request($request);

// Ensure response is properly formatted
if (!is_array($response)) {
    echo json_encode(["error" => "Invalid server response"]);
    exit();
}

echo json_encode($response);
exit();
?>

