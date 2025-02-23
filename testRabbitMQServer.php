#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username, $password)
{
    $host = "100.93.130.48";  // Database host
    $dbuser = "mjn92";        // Database user
    $dbpass = "";             // Database password
    $dbname = "";  // Database name

    // Create a MySQL connection
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);
    
    if ($conn->connect_error) {
        return array('returnCode' => 'failure', 'message' => 'Database connection failed');
    }

    // Prepare the query to fetch the user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch the user record
        $user = $result->fetch_assoc();
        
        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // If valid password
            return array('returnCode' => 'success', 'message' => 'Login successful');
        } else {
            // If invalid password
            return array('returnCode' => 'failure', 'message' => 'Invalid password');
        }
    } else {
        // If no user found
        return array('returnCode' => 'failure', 'message' => 'User not found');
    }

    // Close the database connection
    $conn->close();
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
    echo "Received request" . PHP_EOL;
    var_dump($request);

    if (!isset($request['type'])) {
        return "ERROR: Unsupported message type";
    }

    switch ($request['type']) {
        case "login":
            return doLogin($request['username'], $request['password']);
        case "validate_session":
            return doValidate($request['sessionId']);
        default:
            return array("returnCode" => '0', 'message' => "Server received request and processed");
    }
}

// Create a RabbitMQ server and process requests
$server = new rabbitMQServer("testRabbitMQ.ini", "testServer");
$server->process_requests('requestProcessor');
exit();
?>
