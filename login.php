<?php
if (!isset($_POST) || !isset($_POST['uname']) || !isset($_POST['pword'])) {
    echo json_encode(["message" => "Invalid input data"]);
    exit(0);
}

$username = $_POST['uname'];
$password = $_POST['pword'];

$request = [
    'type' => 'login',
    'username' => $username,
    'password' => $password
];

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>
