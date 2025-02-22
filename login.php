<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once('rabbitMQLib.inc');

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
    
    $request = array();
    $request['type'] = "login";
    $request['username'] = $username;
    $request['password'] = $password;
    
    $response = $client->send_request($request);
    
    if ($response['returnCode'] == 'success') {
        $_SESSION['user'] = $username;
        header("Location: dashboard.php"); // Redirect on success
        exit();
    } else {
        $error = "Login failed. Please check your credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>
        <br>
        <label>Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
