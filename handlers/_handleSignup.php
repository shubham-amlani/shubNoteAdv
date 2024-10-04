<?php
session_start();
include '../partials/_dbconnect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../partials/_sendCode.php';
    $email = $_POST['signupEmail'];
    $name = $_POST['signupName'];
    $username = $_POST['signupUsername'];
    $password = $_POST['signupPassword'];
    $code = rand(100000, 999999);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT COUNT(*) FROM `users` WHERE `user_email`=?";
    $stmt_email = $conn->prepare($sql);
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $stmt_email->bind_result($numRows);
    $stmt_email->fetch();
    $stmt_email->close();
    if ($numRows > 0) {
        $_SESSION['signupError'] = "An account with this email already exists";
        header("Location: ../signup.php");
    } else {
        $sql = "SELECT COUNT(*) FROM `users` WHERE `username`=?";
        $stmt_username = $conn->prepare($sql);
        $stmt_username->bind_param("s", $username);
        $stmt_username->execute();
        $stmt_username->bind_result($numRows);
        $stmt_username->fetch();
        $stmt_username->close();
        if ($numRows > 0) {
            $_SESSION['signupError'] = "Username already taken";
            header("Location: ../signup.php");
        } else {
            if ($password == "" || $password == NULL) {
                $_SESSION['signupError'] = "Password cannot be empty";
                header("Location: ../signup.php");
            } else {
                if ($name == "" || $name == NULL) {
                    $_SESSION['signupError'] = "Name cannot be empty";
                    header("Location: ../signup.php");
                } else {
                    // Store user data into the database
                    $sql = "INSERT INTO `users` (`user_id`, `full_name`, `username`, `user_email`, `user_pass`, `timestamp`, `verification_code`, `is_verified`) VALUES (NULL, ?, ?, ?, ?, current_timestamp(), ?, '0')";
                    $stmt_insert = $conn->prepare($sql);
                    $stmt_insert->bind_param("sssss", $name, $username, $email, $hash, $code);
                    $stmt_insert->execute();
                    sendCode($email, $code);
                    if ($stmt_insert->affected_rows > 0) {
                        $_SESSION['signupEmail'] = $email;
                        header("Location: ../verifycode.php");
                    }
                }
            }
        }
    }
}
?>