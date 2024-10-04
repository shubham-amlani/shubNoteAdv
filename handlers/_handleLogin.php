<?php
session_start();
include '../partials/_dbconnect.php';
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = $_POST['loginEmail'];
        $password = $_POST['loginPassword'];
        $sql = "SELECT * FROM `users` WHERE `user_email`=?";
        $stmt_login = $conn->prepare($sql);
        $stmt_login->bind_param("s", $email);
        $stmt_login->execute();
        $result = $stmt_login->get_result();
        $numRows = $result->num_rows;
        $row = $result->fetch_assoc();
        $stmt_login->close();
        if($numRows==0){
            $_SESSION['loginError'] = "Invalid credentials";
            header("Location: ../login.php");
        }
        else{
            if($row['is_verified']==0){
                include '../partials/_sendCode.php';
                $code = rand(100000, 999999);
                sendCode($email, $code);
                $sql = "UPDATE `users` SET `verification_code`=? WHERE `user_email`=?";
                $stmt_setcode = $conn->prepare($sql);
                $stmt_setcode->bind_param("ss", $code, $email);
                $stmt_setcode->execute();
                if($stmt_setcode->affected_rows > 0){
                    $_SESSION['loginEmail'] = $email;
                    if(isset($_SESSION['signupEmail'])){
                        unset($_SESSION['signupEmail']);
                    }
                    header("Location: ../verifycode.php");
                }  
                $stmt_setcode->close();
            }
            else{
                $dbpass = $row['user_pass'];
                if(password_verify($password, $dbpass)){
                    session_unset();
                    $_SESSION['loggedin']=true;
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['user_email'] = $row['user_email'];
                    $_SESSION['user_bio'] = $row['user_bio'];
                    header("Location: ../home.php");
                }
                else{
                    $_SESSION['loginError'] = "Invalid credentials";
                    header("Location: ../login.php");
                }
            }
        }
    }
?>