<?php
$app_name   = $_POST['app'];
$sender     = $_POST['sender'];
$message    = $_POST['message'];
$phone      = $_POST['phone'];
$group_name = $_POST['group_name'];

// Respond based on the incoming message
$reply = "";

if (strpos(strtolower($message), 'hello') !== false) {
    $reply = "Hi $sender, how can I assist you today?";
} else {
    $reply = "Hello $sender, we received your message: '$message'.";
}

$response = array("reply" => $reply);
echo json_encode($response);
?>
