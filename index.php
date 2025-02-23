<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Include RabbitMQ Client
    require_once('path.inc');
    require_once('get_host_info.inc');
    require_once('rabbitMQLib.inc');

    $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

    $request = array(
        "type" => "login",
        "username" => $username,
        "password" => $password
    );

    $response = $client->send_request($request);

    if ($response['returnCode'] == "success") {
        $_SESSION['username'] = $username;
        echo "<script>alert('Login Successful!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Login Failed! Check your credentials.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
