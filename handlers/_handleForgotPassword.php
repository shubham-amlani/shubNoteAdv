<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include '../partials/_dbconnect.php';
    include '../partials/_sendCode.php';
    $email = $_POST['email'];
    $sql = "SELECT * FROM `users` WHERE `user_email`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $code = rand(100000, 999999);
        sendCode($email, $code);
        $sql_insert_code = "UPDATE `users` SET `verification_code`=? WHERE `user_email`=?";
        $stmt_insert_code = $conn->prepare($sql_insert_code);
        $stmt_insert_code->bind_param("is", $code, $email);
        $stmt_insert_code->execute();
        if($stmt_insert_code->affected_rows > 0){
            $_SESSION['forgotPasswordEmail'] = $email;
            header("Location: ../verifycode.php");
            exit();
        }

    }
    else{
        header("Location: ../verifycode.php");
        exit();
    }
}
?>