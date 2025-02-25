<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Check if username and password are provided
if (!isset($_POST['uname']) || !isset($_POST['pword'])) {
    $response = array('returnCode' => 'error', 'message' => 'Missing username or password');
    echo json_encode($response);
    exit();
}

// Create a new RabbitMQ client
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

// Prepare the request
$request = array();
$request['type'] = "login";
$request['username'] = $_POST['uname'];
$request['password'] = $_POST['pword'];

// Send request to RabbitMQ server
$response = $client->send_request($request);

// Return the response as JSON
echo json_encode($response);
exit();
?>
