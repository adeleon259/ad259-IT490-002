<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['returnCode' => 'failure', 'message' => 'Invalid request']);
    exit;
}

// Get user input
$username = $_POST['username'];
$password = $_POST['password']; // The password should be the hashed password sent from the frontend

// Hash the password before storing it in the database
$hashedPassword = $password; // Since the frontend already hashed the password, no need to hash again

// Create RabbitMQ client instance
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

// Prepare request for RabbitMQ
$request = array();
$request['type'] = "register";
$request['username'] = $username;
$request['password'] = $hashedPassword;

// Send request and get response
$response = $client->send_request($request);

// Log response for debugging
error_log("RabbitMQ response: " . json_encode($response));

// Send response back to frontend
echo json_encode($response);
?>

