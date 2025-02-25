<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username, $password)
{
    // MySQL database connection details
    $host = "100.93.130.48";
    $dbuser = "TeamDog123";
    $dbpass = "TeamDog123";
    $dbname = "users";
    
    // Connect to MySQL database
    $conn = new mysqli($host, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Login successful, return success message
        return array('returnCode' => 'success', 'message' => 'Login successful');
    } else {
        // Login failed, return failure message
        return array('returnCode' => 'failure', 'message' => 'Login failed');
    }

    // Close the database connection
    $conn->close();
}

$request = json_decode(file_get_contents('php://input'), true);
if (isset($request['username']) && isset($request['password'])) {
    echo json_encode(doLogin($request['username'], $request['password']));
} else {
    echo json_encode(array('returnCode' => 'failure', 'message' => 'Invalid request'));
}
?>

