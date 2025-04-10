#!/usr/bin/php
<?php
// rapidAPI.php - Movie data retrieval from RapidAPI

function getMovieData($movieName) {
    $url = "https://moviesdatabase.p.rapidapi.com/titles/search?title=" . urlencode($movieName) . "&limit=1";
    $headers = [
        "x-rapidapi-host: moviesdatabase.p.rapidapi.com",
        "x-rapidapi-key: b982b66939msh3b305adfeb3a70ep1ae7f0jsn69e1de67d99e"
    ];

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the cURL session
    $response = curl_exec($ch);

    // Check for errors
    if(curl_errno($ch)) {
        return array("returnCode" => 1, "message" => "cURL Error: " . curl_error($ch));
    }

    curl_close($ch);

    // Decode and return response
    $responseData = json_decode($response, true);
    if (isset($responseData['results']) && count($responseData['results']) > 0) {
        return array("returnCode" => 0, "message" => "Movie data", "data" => $responseData['results'][0]);
    } else {
        return array("returnCode" => 1, "message" => "Movie not found");
    }
}
?>
