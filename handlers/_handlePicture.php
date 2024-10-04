<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    include '../partials/_dbconnect.php';
    $user_id = $_POST['user_id'];

    // Define the SQL query to update the image path
    $sql = "UPDATE `users` SET `image_path` = ? WHERE `user_id` = ?";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    $uploadDir = "../user_images/";
    $uploadFile = $uploadDir . basename($_FILES["image"]["name"]);
    // Bind parameters
    $dbpath = str_replace("../", "", $uploadFile);
    echo $dbpath;
    $stmt->bind_param("si", $dbpath, $user_id);

    // Define upload directory and file path

    // Validate uploaded file
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "jfif");
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($fileType, $allowedExtensions)) {
        $_SESSION['uploadError'] = "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
    } elseif ($_FILES["image"]["size"] > $maxFileSize) {
        $_SESSION['uploadError'] = "Error: File size exceeds the limit.";
    } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
        // Execute the SQL query to update the image path in the database
        $stmt->execute();
        $_SESSION['uploadSuccess'] = "File uploaded successfully.";
    } else {
        $_SESSION['uploadError'] = "Error uploading file.";
    }

    // Close the prepared statement
    $stmt->close();

    // Redirect back to the account page
    header("Location: ../myaccount.php");
    exit(); // Make sure to exit after redirection
}
?>