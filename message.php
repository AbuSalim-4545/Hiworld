<?php
// Get incoming message data from WhatAuto
$app_name   = $_POST["app"];
$sender     = $_POST["sender"];
$message    = $_POST["message"];
$phone      = $_POST["phone"];
$group_name = $_POST["group_name"];

// Optionally, check if this is a custom message trigger (based on configuration)
$custom_message = "your_custom_message"; // You can dynamically configure this

if ($message === $custom_message) {
    // If it matches a custom message, proceed with ChatGPT response logic
    $response = generateChatGPTResponse($message);
} else {
    // Fallback reply
    $response = array("reply" => "Hello $sender, we received your message: $message.");
}

echo json_encode($response);

// Function to interact with the OpenAI API and get the response
function generateChatGPTResponse($message) {
    $apiKey = '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';  // Replace with your actual OpenAI API key

    // Create request body
    $data = array(
        "model" => "gpt-4",
        "messages" => array(
            array("role" => "user", "content" => $message)
        ),
        "temperature" => 0.7
    );

    // Initialize cURL
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute and fetch the result
    $result = curl_exec($ch);
    curl_close($ch);

    // Process and return the response
    $resultArray = json_decode($result, true);
    $reply = $resultArray['choices'][0]['message']['content'];

    return array("reply" => $reply);
}
?>
