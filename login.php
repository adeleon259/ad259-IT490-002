<?php

// Include RabbitMQ library (needed for messaging system)
require_once('path.inc'); //loads path configuration, found on W3
require_once('get_host_info.inc'); // loads host details
require_once('rabbitMQLib.inc'); // loads rabbitMQ client function

// Only accept POST requests ensures security 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['returnCode' => 'failure', 'message' => 'Invalid request method']);
    exit;
}

// Read the data sent from the frontend
$request = $_POST;

// Make sure all required fields are present
if (!isset($request["type"]) || !isset($request["username"]) || !isset($request["password"])) {
    echo json_encode(['returnCode' => 'failure', 'message' => 'Missing required fields']);
    exit;
}

// connects to rabbitMQ by creating RabbitMQ client instance
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

// Prepare request for RabbitMQ
$rabbit_request = [
    "type" => "login", // Tells rabbitmq its a login request 
    "username" => $request["username"],
    "password" => $request["password"]
];

// Send request to RabbitMQ and wait for response
$response = $client->send_request($rabbit_request);

// Send the response back to the frontend as JSON
echo json_encode($response);
exit;
?>
