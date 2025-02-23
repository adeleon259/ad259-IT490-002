#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username, $password)
{
    // Database connection parameters
    $host = "100.93.130.48";  // Your MySQL server IP address
    $dbuser = "mjn92";         // Your MySQL username
    $dbpass = "";              // Your MySQL password
    $dbname = "movDB";         // Your database name

    // Create a connection to the MySQL database
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        return array('returnCode' => 'failure', 'message' => 'Connection failed: ' . $conn->connect_error);
    }

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user and check the password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) { // Use password_verify for hashed passwords
            return array('returnCode' => 'success', 'message' => 'Login successful');
        }
    }
    return array('returnCode' => 'failure', 'message' => 'Login failed');
}

function doValidate($sessionId)
{
    if ($sessionId === 'validSession') {
        return array('returnCode' => 'success', 'message' => 'Session valid');
    }
    return array('returnCode' => 'failure', 'message' => 'Invalid session');
}

function requestProcessor($request)
{
    echo "Received request".PHP_EOL;
    var_dump($request);
    
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }

    switch ($request['type']) {
        case "login":
            return doLogin($request['username'], $request['password']);
        case "validate_session":
            return doValidate($request['sessionId']);
    }
    
    return array("returnCode" => '0', 'message' => "Server received request and processed");
}

// Initialize RabbitMQ server
$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
$server->process_requests('requestProcessor');
exit();
?>


