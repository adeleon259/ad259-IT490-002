<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doRegister($username, $password)
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

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists, return failure
        return array('returnCode' => 'failure', 'message' => 'Username already taken');
    }

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        // Registration successful, return success
        return array('returnCode' => 'success', 'message' => 'User registered successfully');
    } else {
        // Error during registration, return failure
        return array('returnCode' => 'failure', 'message' => 'Registration failed');
    }

    // Close the database connection
    $conn->close();
}

$request = json_decode(file_get_contents('php://input'), true);
if (isset($request['username']) && isset($request['password'])) {
    echo json_encode(doRegister($request['username'], $request['password']));
} else {
    echo json_encode(array('returnCode' => 'failure', 'message' => 'Invalid request'));
}
?>
