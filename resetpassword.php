<?php
session_start();
if(!isset($_SESSION['forgotPasswordEmail'])){
    header("Location: index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_SESSION['forgotPasswordEmail'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if(($password == $cpassword) && $password!=NULL && $password!=''){
        include 'partials/_dbconnect.php';
        $hash = password_hash($cpassword, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `user_pass`=? WHERE `user_email`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $_SESSION['resetPasswordMessage'] = "Your password is reset, you can login now.";
            header("Location: login.php");
        }
        else{
            $_SESSION['resetPasswordError'] = "Cannot update password at the moment, please try again later";
            header("Location: login.php"); 
        }
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - shubNote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php include 'partials/_styles.php';?>
</head>

<body>
    <div class="container p-2 height">
        <h1>Set a new password</h1>
        <form action="resetpassword.php" method='post'>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Create a strong password">
            </div>
            <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword"
                    placeholder="Confirm your password">
            </div>
            <button type="submit" class="btn btn-primary">Reset my password</button><br>
        </form>
    </div>
    <?php include 'partials/_footer.php';?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>