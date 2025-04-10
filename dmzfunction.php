<?php

function doLogin($username, $password) {
    $command = escapeshellcmd("./checkUser.php '$username' '$password'");
    $output = shell_exec($command);

    if (preg_match('/\baccept\b/', $output)) {
        return array("returnCode" => 0, "message" => "Login successful");
    }
    return array("returnCode" => 1, "message" => "Invalid credentials");
}

function makeUser($username, $email, $password) {
    $command = escapeshellcmd("./makeUser.php '$username' '$email' '$password'");
    $output = shell_exec($command);

    if (preg_match('/\bcreated\b/', $output)) {
        return array("returnCode" => 0, "message" => "User Created");
    }
    return array("returnCode" => 1, "message" => "Failed to create user");
}

function doValidate($username) {
    $command = escapeshellcmd("./valSession.php '$username'");
    $output = shell_exec($command);

    if (preg_match('/\baccept\b/', $output)) {
        return array("returnCode" => 0, "message" => "User is logged in.");
    }
    return array("returnCode" => 1, "message" => "User is logged out.");
}

function makeUserPref($username, $comedy, $drama, $horror, $romance, $sci_fi) {
    $command = escapeshellcmd("./makeUserPref.php '$username' '$comedy' '$drama' '$horror' '$romance' '$sci_fi'");
    $output = shell_exec($command);

    if (preg_match('/\binserted\b/', $output)) {
        return array("returnCode" => 0, "message" => "User preferences saved");
    }
    return array("returnCode" => 1, "message" => "Failed to save user preferences");
}

function checkUserPref($username) {
    $command = escapeshellcmd("./checkUserPref.php '$username'");
    $output = shell_exec($command);
    return array("returnCode" => 0, "message" => "User preferences", "data" => $output);
}

function checkMovie($movieName) {
    $command = escapeshellcmd("./checkMovie.php '$movieName'");
    $output = shell_exec($command);

    if (strpos($output, "Movie not found in database.") !== false) {
        $command = escapeshellcmd("./getMovieRabbitMQ.php '$movieName'");
        $output = shell_exec($command);
    }

    return array("returnCode" => 0, "message" => "Movie data", "data" => $output);
}

function addFriend($username, $friendName) {
    $command = escapeshellcmd("./makeFriends.php '$username' '$friendName'");
    $output = shell_exec($command);

    if (preg_match('/\bsuccessfully\b/', $output)) {
        return array("returnCode" => 0, "message" => "Friend added successfully");
    }
    return array("returnCode" => 1, "message" => "Failed to add friend");
}

function getFriends($username) {
    $command = escapeshellcmd("./checkFriends.php '$username'");
    $output = shell_exec($command);
    return array("returnCode" => 0, "message" => "User's friends list", "data" => $output);
}
?>
