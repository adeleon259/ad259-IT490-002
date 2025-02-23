<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the POST variables
    $username = $_POST['uname'];
    $password = $_POST['pword'];

    // Send the data to RabbitMQ for validation
    $response = SendLoginRequestToRabbitMQ($username, $password); // Function to connect to RabbitMQ

    // Handle the response from RabbitMQ
    echo json_encode($response);
    exit(0);
} else {
    echo json_encode("Invalid request type");
    exit(0);
}

function SendLoginRequestToRabbitMQ($username, $password) {
    // Your RabbitMQ connection and login logic here
    // Example (ensure to adjust based on your RabbitMQ server configuration):
    try {
        $connection = new AMQPStreamConnection('100.87.150.55', 5672, 'test', 'test', 'testHost');
        $channel = $connection->channel();

        // Define and send the login request to RabbitMQ
        $msg = new AMQPMessage(json_encode([
            'type' => 'login',
            'username' => $username,
            'password' => $password
        ]));
        $channel->basic_publish($msg, 'testExchange', 'testQueue');

        // Wait for a response
        $response = $channel->basic_get('testQueue');
        if ($response) {
            return json_decode($response->body, true);
        } else {
            return 'No response from RabbitMQ';
        }

    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}
?>
