<?php
session_start();
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header("Location: myaccount.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - shubNote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php include 'partials/_styles.php';?>
    <style>
    body {
        font-family: 'Ubuntu', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    .container-forgot-password {
        max-width: 400px;
        margin: 0 auto;
        padding: 50px 20px;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        border: none;
        border-bottom: 2px solid #007bff;
        border-radius: 0;
        background-color: transparent;
        transition: border-bottom-color 0.3s;
        font-size: 16px;
        outline: none;
    }

    .form-control:focus {
        border-bottom-color: #0056b3;
    }

    .form-control::placeholder {
        opacity: 0.6;
    }

    .btn-send-email {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    .btn-send-email:hover {
        background-color: #0056b3;
    }

    .btn-login {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }

    .btn-login:hover {
        color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container-forgot-password mt-5 ">
        <h2 class="mb-4">Forgot Your Password?</h2>
        <p>No worries! Enter your email below and we'll send you instructions on how to reset your password.</p>
        <form action="handlers/_handleForgotPassword.php" method="POST">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <button type="submit" class="btn btn-send-email">Send Reset Instructions</button>
        </form>
        <p class="mt-4">Remember your password? <a href="login.php" class="btn-login">Login</a></p>
    </div>

    <footer class="footer bg-dark py-3 fixed-bottom">
        <div class="container text-center text-light p-0">
            <div class="row justify-content-center ">
                <div class="col-md-6">
                    <p class="mb-0">Designed and developed by Shubham Amlani</p>
                    <p class="mb-0">All rights reserved &copy; 2024</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>