<?php
// Ensure we're handling the POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['type'])) {
    // Retrieve the username and password from the POST data
    $username = $_POST['uname'];
    $password = $_POST['pword'];

    // Send the data to RabbitMQ for validation
    $response = SendLoginRequestToRabbitMQ($username, $password);

    // Send the response back to the client
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
        // Connect to RabbitMQ server
        $connection = new AMQPStreamConnection('100.87.150.55', 5672, 'test', 'test', 'testHost');
        $channel = $connection->channel();

        // Create the login request message
        $msg = new AMQPMessage(json_encode([
            'type' => 'login',
            'username' => $username,
            'password' => $password
        ]));
        
        // Publish the message to RabbitMQ
        $channel->basic_publish($msg, 'testExchange', 'testQueue');

        // Wait for the response
        $response = $channel->basic_get('testQueue');
        if ($response) {
            // Return the response from RabbitMQ
            return json_decode($response->body, true);
        } else {
            return ['message' => 'No response from RabbitMQ'];
        }
    } catch (Exception $e) {
        return ['message' => 'Error: ' . $e->getMessage()];
    }
}
?>
