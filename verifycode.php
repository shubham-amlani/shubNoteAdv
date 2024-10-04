<?php
session_start();
if (isset($_SESSION['signupEmail'])) {
    $email = $_SESSION['signupEmail'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'partials/_dbconnect.php';
        $sql = "SELECT * FROM `users` WHERE `user_email`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Checking whether the entered code is same or not.
        $enteredCode = $_POST['veri_code'];
        $actualCode = $row['verification_code'];
        echo var_dump($enteredCode == $actualCode);
        if ($enteredCode == $actualCode) {
            $sql = "UPDATE `users` SET `is_verified` = '1' WHERE `users`.`user_email` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $_SESSION['isverified'] = true;
                header("Location: login.php");
            }
        }
    }
}

$displayLoginVerificationMessage = false;
if (isset($_SESSION['loginEmail'])) {
    $displayLoginVerificationMessage = true;
    $email = $_SESSION['loginEmail'];
    echo var_dump($_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include 'partials/_dbconnect.php';
        $sql = "SELECT * FROM `users` WHERE `user_email`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Check whether entered code is same or not
        $enteredCode = $_POST['veri_code'];
        $actualCode = $row['verification_code'];
        if ($enteredCode == $actualCode) {
            $sql = "UPDATE `users` SET `is_verified` = '1' WHERE `users`.`user_email` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $_SESSION['isverified'] = true;
                header("Location: login.php");
            }
        }
        else{
            $_SESSION['verification_error'] = "Invalid code entered";
        }
    }
}

if (isset($_SESSION['forgotPasswordEmail'])) {
    include 'partials/_dbconnect.php';
    $email = $_SESSION['forgotPasswordEmail'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql_forgot_password = "SELECT `verification_code` FROM `users` WHERE `user_email`=?";
        $stmt_forgot_password = $conn->prepare($sql_forgot_password);
        $stmt_forgot_password->bind_param("s", $email);
        $stmt_forgot_password->execute();
        $result_forgot_password = $stmt_forgot_password->get_result();
        $row_forgot_password = $result_forgot_password->fetch_assoc();

        $dbCode = $row_forgot_password['verification_code'];
        $entCode = $_POST['veri_code'];
        if ($entCode == $dbCode) {
            header("Location: resetpassword.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - shubNote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <?php include 'partials/_styles.php'; ?>
    <style>
    body {
        font-family: 'Ubuntu', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container-verify-code {
        max-width: 400px;
        margin: 50px auto;
        padding: 50px 20px;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 30px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: border-color 0.3s;
        font-size: 16px;
        outline: none;
    }

    .form-control:focus {
        border-color: #007bff;
    }

    .btn-verify-code {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 16px;
    }

    .btn-verify-code:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container-verify-code">
        <?php
    if($displayLoginVerificationMessage){
    echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Verify!</strong> Your email is not verified, you need to verify before you login.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }

    if(isset($_SESSION['verification_error'])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong>'.$_SESSION['verification_error'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['verification_error']);
        }
    ?>
        <h2>Verify Your Email</h2>
        <p>We've sent a six-digit verification code to your email. Please enter it below to complete the verification
            process.</p>
        <form action="verifycode.php" method="POST">
            <div class="form-group">
                <input type="number" class="form-control" name="veri_code" placeholder="Enter 6 digit Verification Code"
                    required>
            </div>
            <button type="submit" class="btn btn-verify-code">Verify Code</button>
        </form>
        <p class="mt-4">Didn't receive the code? Please wait for some time or check your spam folder.</p>
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