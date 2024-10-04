<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    header("Location: index.php");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include 'partials/_dbconnect.php';
    $sql_change_pass = "SELECT * FROM `users` WHERE `user_id`=?";
    $stmt_change_pass = $conn->prepare($sql_change_pass);
    $stmt_change_pass->bind_param("i", $_SESSION['user_id']);
    $stmt_change_pass->execute();
    $result_change_pass = $stmt_change_pass->get_result();
    $row_change_pass = $result_change_pass->fetch_assoc();
    $current_pass = $row_change_pass['user_pass'];

    $old_pass = $_POST['oldPass'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_SESSION['user_email'];
    if(password_verify($old_pass, $current_pass) && ($password == $cpassword) && $password!=NULL && $password!=''){
        $hash = password_hash($cpassword, PASSWORD_DEFAULT);
        $sql = "UPDATE `users` SET `user_pass`=? WHERE `user_email`=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();
        if($stmt->affected_rows > 0){
            $_SESSION['changePassMessage'] = "Your password is reset";
            header("Location: editProfile.php");
        }
        else{
            $_SESSION['changePassError'] = "Cannot update password, please recheck your credentials";
            header("Location: editProfile.php");
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
        <form action="changepassword.php" method='post'>
        <div class="mb-3">
                <label for="oldPass" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="oldPass" name="oldPass"
                    placeholder="Enter your current password">
            </div>
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
    <?php include 'partials/_scripts.php';?>
</body>

</html>