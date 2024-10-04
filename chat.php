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
            max-width: 800px;
        }

        .main {
            background-color: #f0f4f8;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .user-information {
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        .username {
            color: #333;
            font-weight: bold;
        }

        .chat-box {
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: 60vh;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            background-color: #ffffff;
            margin-top: 15px;
        }

        .message-sent {
            background-color: #dcf8c6;
            margin: 5px 0;
            padding: 10px;
            border-radius: 20px;
            align-self: flex-end;
            max-width: 80%;
            word-wrap: break-word;
        }

        .message-received {
            background-color: #ffffff;
            margin: 5px 0;
            padding: 10px;
            border-radius: 20px;
            align-self: flex-start;
            max-width: 80%;
            word-wrap: break-word;
        }

        .message-timestamp {
            font-size: 0.7em;
            color: gray;
            display: block;
            margin-top: 5px;
            text-align: right;
        }

        .message-input {
            display: flex;
            align-items: center;
            padding-top: 15px;
        }

        .message-input-field {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
            font-size: 1em;
        }

        .send-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50%;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            .chat-box {
                height: 50vh;
            }
        }
    </style>
</head>

<body class="d-flex justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>

    <main class="main mx-2 container-md">
        <div class="user-information d-flex align-items-center mb-3">
            <div class="user-image" style="margin-right: 15px;">
                <?php echo displayUserImage($user_id); ?>
            </div>
            <div>
                <span class="username h5 mb-0"><?php echo $username; ?></span><br>
                <span class="text-muted" style="font-size: 0.9em;"><?php echo $name; ?></span>
            </div>
        </div>

        <div class="chat-box" id="chat-box">
            <!-- Messages will be displayed here dynamically -->
        </div>

        <div class="message-input">
            <input type="text" id="message-input" class="message-input-field" placeholder="Type a message" />
            <button class="send-button" id="send-button">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </main>

    <?php include 'partials/_bottomNav.php'; ?>

    <script>
        const profileId = <?php echo $user_id; ?>; // Receiver ID
        const senderId = <?php echo $_SESSION['user_id']; ?>; // Sender ID

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

        const conn = new WebSocket(`ws://localhost:1234/chat?user_id=${senderId}`); // WebSocket connection

        conn.onopen = function() {
            console.log("Connected to WebSocket server.");
        };

        conn.onmessage = function(e) {
            const data = JSON.parse(e.data);
            if (data.sender == senderId) {
                return; // Ignore sent messages
            } else if (data.sender == profileId) {
                displayMessage("message-received", data.message, data.timestamp);
            }
        };

        function displayMessage(type, message, timestamp = null) {
            const chatWindow = document.getElementById("chat-box");
            const messageElement = document.createElement("div");

            let messageTime;
            if (timestamp) {
                messageTime = new Date(timestamp);
            } else {
                messageTime = new Date(); // Use current time if no timestamp
            }

            const timeDisplay = messageTime.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            messageElement.classList.add(type);
            messageElement.innerHTML = `${message} <span class="message-timestamp">${timeDisplay}</span>`;

            chatWindow.appendChild(messageElement);
            chatWindow.scrollTop = chatWindow.scrollHeight; // Auto-scroll to the bottom
        }

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

            const data = {
                profile_id: profileId,
                sender_id: senderId,
                message: message,
                timestamp: new Date().toISOString() // UTC timestamp
            };

            conn.send(JSON.stringify(data));
            displayMessage("message-sent", message, data.timestamp); // Display locally
            messageInput.value = ""; // Clear input field
        }
    </script>
</body>

</html>
