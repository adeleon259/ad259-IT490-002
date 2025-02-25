<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Check if username and password are provided
if (!isset($_POST['uname']) || !isset($_POST['pword'])) {
    $response = array('returnCode' => 'error', 'message' => 'Missing username or password');
    echo json_encode($response);
    exit();
}

$username = $_POST['uname'];
$password = $_POST['pword'];

echo "Received login request for user: " . $username . "<br>";

// Create a new RabbitMQ client
try {
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    if (!$client) {
        throw new Exception("Failed to create RabbitMQ client.");
    }
} catch (Exception $e) {
    echo json_encode(array("returnCode" => "error", "message" => "RabbitMQ client error: " . $e->getMessage()));
    exit();
}

// Prepare the request
$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;

// Send request to RabbitMQ server
try {
    $response = $client->send_request($request);

    if (!$response) {
        throw new Exception("No response from RabbitMQ.");
    }
} catch (Exception $e) {
    echo json_encode(array("returnCode" => "error", "message" => "RabbitMQ request error: " . $e->getMessage()));
    exit();
}

// Debugging output: show response from RabbitMQ
echo "Response from RabbitMQ: ";
print_r($response);

echo json_encode($response);
exit();
?>
