<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Function to send login data to RabbitMQ
function sendToRabbitMQ($username, $password)
{
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

    // Send login request to RabbitMQ
    $request = array();
    $request['type'] = "login";
    $request['username'] = $username;
    $request['password'] = $password;

    // Get response from RabbitMQ
    $response = $client->send_request($request);

    return $response;
}

// Handle the POST request
$request = json_decode(file_get_contents('php://input'), true);

if (isset($request['username']) && isset($request['password'])) {
    // Get the login details from the request
    $username = $request['username'];
    $password = $request['password'];

    // Send the credentials to RabbitMQ and get the response
    $response = sendToRabbitMQ($username, $password);

    // Check if the login was successful or failed
    if ($response['returnCode'] === 'success') {
        echo json_encode(array('returnCode' => 'success', 'message' => 'Login successful'));
    } else {
        echo json_encode(array('returnCode' => 'failure', 'message' => 'Login failed'));
    }
} else {
    echo json_encode(array('returnCode' => 'failure', 'message' => 'Invalid request'));
}
?>


