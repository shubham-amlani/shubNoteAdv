<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    header("Location: myaccount.php");
}
$showVerificationMessage = false;
$showLoginError = false;
if(isset($_SESSION['isverified']) && $_SESSION['isverified']==true){
    $showVerificationMessage = true;
}

if(isset($_SESSION['loginError'])){
    $showLoginError = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - shubNote</title>
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

    .container-login {
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

    .btn-login {
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

    .btn-login:hover {
        background-color: #0056b3;
    }

    .btn-signup {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }

    .btn-signup:hover {
        color: #0056b3;
    }

    .forgot-password {
        color: #007bff;
        text-decoration: none;
        transition: color 0.3s;
    }

    .forgot-password:hover {
        color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container-login my-5">
        <?php
    if($showVerificationMessage){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your email is verified, you can login now.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['isverified']);
    }
    if($showLoginError){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Sorry! </strong>'.$_SESSION['loginError'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
        unset($_SESSION['loginError']);
    }
    if(isset($_SESSION['resetPasswordMessage'])){
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success! </strong>'.$_SESSION['resetPasswordMessage'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['resetPasswordMessage']);
    }
    if(isset($_SESSION['resetPasswordError'])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Sorry! </strong>'.$_SESSION['resetPasswordError'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['resetPasswordError']);
    }
    ?>
        <h2 class="mb-4">Login to <span class="highlight">shubNote</span></h2>
        <form action="handlers/_handleLogin.php" method="POST" style="width: 100%;">
            <div class="form-group">
                <input type="email" class="form-control" name="loginEmail" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="loginPassword" placeholder="Password" required>
            </div>
            <a href="myaccount.php" class="nav-link p-0"><button type="submit" class="btn btn-login">Login</button></a>
        </form>
        <p class="mt-3"><a href="forgotPassword.php" class="forgot-password">Forgot Password?</a></p>
        <p class="mt-4">Don't have an account? <a href="signup.php" class="btn-signup">Sign up</a></p>
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
    <?php include 'partials/_scripts.php';?>
</body>

</html>