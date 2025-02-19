<?php
// Database connection details
$host = "localhost";
$dbname = "user_auth";
$user = "root";  // Change this if needed
$pass = "";      // Change this if needed

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the username and password from the form
    $uname = $_POST['uname'];
    $pword = password_hash($_POST['pword'], PASSWORD_DEFAULT); // Hash the password before storing

    // Prepare SQL statement to insert the new user
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $uname, $pword);

    // Execute and check if the registration was successful
    if ($stmt->execute()) {
        echo json_encode(["success" => "Registration successful, please login"]);
    } else {
        echo json_encode(["error" => "Username already exists"]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
