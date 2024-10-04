<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: index.php");
    exit();
}

include 'partials/_dbconnect.php';
$user_id = $_GET['profileid'];

if ($_SESSION['user_id'] == $user_id) {
    header("Location: myaccount.php");
    exit();
}

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $name = $row['full_name'];
} else {
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Chat - shubNote</title>
    <?php include 'partials/_styles.php'; ?>
    <style>
        /* Styles for the chat box */
        .container-md {
            width: 50%;
            padding: 10px;
            margin-top: 100px;
        }

        .message-sent {
            background-color: #dcf8c6;
            margin: 5px 0;
            padding: 8px;
            border-radius: 10px;
            float: right;
            clear: both;
            max-width: 80%;
            word-wrap: break-word;
        }

        .message-received {
            background-color: #ffffff;
            margin: 5px 0;
            padding: 8px;
            border-radius: 10px;
            float: left;
            clear: both;
            max-width: 80%;
            word-wrap: break-word;
        }

        .chat-box {
    overflow-y: auto;
    min-height: 60vh; /* Minimum height to cover a good portion of the screen */
    max-height: calc(100vh - 200px); /* Dynamic height based on viewport */
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #f9f9f9;
}

@media (max-width: 768px) {
    .chat-box {
        min-height: 50vh;
        max-height: calc(100vh - 150px);
    }
}

        .message-timestamp {
            font-size: 0.8em;
            color: gray;
            display: block;
        }

        .message-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message-input input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
        }

        .message-input button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .container-md {
                width: 100%;
            }
        }
    </style>
</head>

<body class="d-flex justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>

    <!-- Main content -->
    <main class="main mx-2 container-md p-2">
        <div class="user-information">
            <?php echo displayUserImage($user_id); ?>
            <div>
                <span class="username py-0"><?php echo $username; ?></span><br>
                <span><?php echo $name; ?></span>
            </div>
        </div>

        <div class="chat-box" id="chat-box">
            <!-- Messages will be displayed here dynamically -->
        </div>

        <div class="message-input">
            <input type="text" id="message-input" class="py-1" placeholder="Type a message">
            <button class="btn btn-primary send-button" id="send-button"><i class="fa fa-paper-plane"></i></button>
        </div>
    </main>

    <?php include 'partials/_bottomNav.php'; ?>

    <!-- WebSocket Chat functionality -->
    <script>
        window.onload = function() {
        loadChatHistory();

        function loadChatHistory() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "load_chat.php?profile_id=" + profileId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const messages = JSON.parse(xhr.responseText);
                    messages.forEach(message => {
                        const type = (message.sender_id == senderId) ? "message-sent" : "message-received";
                        displayMessage(type, message.message, message.timestamp);
                    });
                }
            };
            xhr.send();
        }
    };
const profileId = <?php echo $user_id; ?>; // Receiver ID (user being chatted with)
const senderId = <?php echo $_SESSION['user_id']; ?>; // Sender ID (current logged in user)
const conn = new WebSocket(`ws://192.168.1.71:1234/chat?user_id=${senderId}`); // Include user ID in the query string


conn.onopen = function() {
    console.log("Connected to WebSocket server.");
};

conn.onmessage = function(e) {
    const data = JSON.parse(e.data);
    // Check if the message is from the current user or the other user
    if (data.sender == senderId) {
        // Message sent by the current user (sender)
        return;
    } else if (data.sender == profileId) {
        // Message sent by the other user (receiver)
        displayMessage("message-received", data.message, data.timestamp);
    } else {
        console.log("Message received but sender doesn't match any of the users.");
    }
};



// Function to display the message
function displayMessage(type, message, timestamp = null) {
    const chatWindow = document.getElementById("chat-box");
    const messageElement = document.createElement("div");

    // Format the timestamp
    const messageTime = timestamp ? new Date(timestamp) : new Date();
    let timeDisplay = messageTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    // Set the message class based on whether it's sent or received
    messageElement.classList.add(type);
    messageElement.innerHTML = `${message} <br> <small>${timeDisplay}</small>`;

    chatWindow.appendChild(messageElement);
    chatWindow.scrollTop = chatWindow.scrollHeight; // Auto scroll to the bottom
}

// Send message when "Send" button is clicked
document.getElementById("send-button").addEventListener("click", sendMessage);

document.getElementById("message-input").addEventListener("keypress", function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

function sendMessage() {
    const messageInput = document.getElementById("message-input");
    const message = messageInput.value.trim();

    if (message === "") {
        return; // Don't send empty messages
    }

    // Prepare the message data
    const data = {
        profile_id: profileId, // Receiver's profile ID (the other user)
        sender_id: senderId, // Sender's user ID (current logged in user)
        message: message,
        timestamp: new Date().toISOString() // Add a valid timestamp
    };

    conn.send(JSON.stringify(data)); // Send message as JSON
    displayMessage("message-sent", message, new Date().toISOString()); // Display the sent message immediately
    messageInput.value = ""; // Clear the input
}

</script>
</body>

</html>
