<?php
// This PHP script handles the incoming form data and interacts with OpenAI's API

// Get form data from POST request
$prompt = $_POST["prompt"] ?? '';

// Handle file upload if present
if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    
    // Ensure the uploads directory exists
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Move the uploaded file to the 'uploads' directory
    move_uploaded_file($file_tmp, "uploads/" . basename($file_name));
}

// Include current time in the prompt
$current_time = date('Y-m-d H:i:s');
$prompt_with_time = "At $current_time, the user submitted: $prompt";

// OpenAI API request
$api_key = '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';  // Replace with your actual API key

$data = [
    "model" => "gpt-4",
    "messages" => [
        ["role" => "user", "content" => $prompt_with_time]
    ],
    "temperature" => 0.7
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.openai.com/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ]
]);

$response = curl_exec($curl);

// Check for cURL errors
if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
} else {
    // Decode API response
    $response_data = json_decode($response, true);
    $chatgpt_reply = $response_data['choices'][0]['message']['content'] ?? 'No response from ChatGPT';
    
    // Return a custom reply in JSON format
    $result = array("reply" => $chatgpt_reply);
    echo json_encode($result);
}

curl_close($curl);
?>
