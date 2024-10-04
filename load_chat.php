<?php
include 'partials/_dbconnect.php';
session_start();

date_default_timezone_set('Asia/Kolkata'); // Set this to your appropriate timezone

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['profile_id'];

// Fetch chat history between two users
$sql = "SELECT * FROM chats WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    // Convert UTC timestamp to local timezone
    $utcDateTime = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
    $utcDateTime->setTimezone(new DateTimeZone('Asia/Kolkata')); // Change to your timezone
    $localTimestamp = $utcDateTime->format('Y-m-d H:i:s');

    // Append the message along with the converted timestamp
    $messages[] = [
        'sender_id' => $row['sender_id'],
        'receiver_id' => $row['receiver_id'],
        'message' => $row['message'],
        'timestamp' => $localTimestamp // Use local timestamp here
    ];
}

// Return the messages as JSON
echo json_encode($messages);
?>
