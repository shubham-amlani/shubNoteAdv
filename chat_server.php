<?php
require __DIR__ . '/vendor/autoload.php';
include 'partials/_dbconnect.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $userConnections;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        try{
            $this->db = new PDO("mysql:host=localhost;dbname=shubnote;charset=utf8", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            exit; 
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);

        // Use query parameters to map user ID to the connection
        $queryParams = [];
parse_str($conn->httpRequest->getUri()->getQuery(), $queryParams);

if (isset($queryParams['user_id'])) {
    $userId = $queryParams['user_id'];
    $this->userConnections[$userId][] = $conn;
    $conn->userId = $userId;
    echo "New connection from user $userId ({$conn->resourceId})\n";
} else {
    echo "User ID not provided. Closing connection.\n";
    $conn->close(); // Close connection if no user ID is provided
}

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        global $conn; // Assuming $conn is your MySQLi connection object
    
        $data = json_decode($msg);
        if ($data === null) {
            echo "Invalid JSON received: $msg\n";
            return;
        }
    
        $senderId = $from->userId;
        $receiverId = $data->profile_id; 
        $message = $data->message;
        $timestamp = date('Y-m-d H:i:s'); // Store timestamp in MySQL format
    
        // Insert message into the database
        $stmt = $conn->prepare("INSERT INTO chats (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $senderId, $receiverId, $message, $timestamp);
        $stmt->execute();
    
        // Prepare the message data to send to clients
        $messageData = [
            'sender' => $senderId,
            'message' => $message,
            'timestamp' => $timestamp
        ];
    
        // Send message to receiver if their connection exists
        if (isset($this->userConnections[$receiverId])) {
            foreach ($this->userConnections[$receiverId] as $receiverConn) {
                $receiverConn->send(json_encode($messageData));
            }
        }
    
        // Optionally send to the sender as well
        $from->send(json_encode($messageData));
    }
    
    

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection when it closes
        $this->clients->detach($conn);

        // Handle user connection cleanup
        if (isset($conn->userId)) {
            $userId = $conn->userId;
            if (isset($this->userConnections[$userId])) {
                // Remove this specific connection from the user's connection list
                $this->userConnections[$userId] = array_filter(
                    $this->userConnections[$userId],
                    function($connection) use ($conn) {
                        return $connection !== $conn;
                    }
                );

                // If no more connections exist for this user, remove them from the map
                if (empty($this->userConnections[$userId])) {
                    unset($this->userConnections[$userId]);
                }
            }
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        // Handle connection errors
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Create WebSocket server and run it in an event loop
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    1234 // Use the desired port number
);

echo "WebSocket server running on port 1234\n";
$server->run();