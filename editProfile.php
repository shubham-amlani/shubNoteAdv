<?php
session_start();
include 'partials/_functions.php';
include 'partials/_sessionVars.php';
if(!(isset($_SESSION['loggedin'])) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include 'partials/_dbconnect.php';
    $bio = $_POST['bio'];
    $name = $_POST['name'];
    $username = $_POST['username'];

    $sql_edit_profile = "SELECT * FROM `users` WHERE `user_id`=?";
    $stmt_edit_profile = $conn->prepare($sql_edit_profile);
    $stmt_edit_profile->bind_param('i', $_SESSION['user_id']);
    $stmt_edit_profile->execute();
    $result_edit_profile = $stmt_edit_profile->get_result();
    $row_edit_profile = $result_edit_profile->fetch_assoc();
    $current_username = $row_edit_profile['username'];

    if($current_username == $username){
        $sql = "UPDATE `users` SET `user_bio`=?, `username`=?, `full_name`=? WHERE `users`.`user_id` = ?";
        $stmt_updatebio = $conn->prepare($sql);
        $stmt_updatebio->bind_param("ssss", $bio, $username, $name, $user_id);
        $stmt_updatebio->execute();
        
        if($stmt_updatebio->affected_rows > 0){
            $_SESSION['user_bio'] = $bio;
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $name;
            $_SESSION['editProfileSuccess'] = 'Profile updated successfully';
            header("Location: editProfile.php");
            exit;
        } else {
            $_SESSION['editProfileError'] = 'Cannot update profile';
            header("Location: editProfile.php");
        }
        $stmt_updatebio->close();
    }
    else{
        $sql_check_user = "SELECT * FROM `users` WHERE `username`=?";
        $stmt_check_user = $conn->prepare($sql_check_user);
        $stmt_check_user->bind_param('s', $username);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();
        if($result_check_user->num_rows > 0){
            $_SESSION['editProfileError'] = 'Username already exists';
        }
        else{
            $sql = "UPDATE `users` SET `user_bio`=?, `username`=?, `full_name`=? WHERE `users`.`user_id` = ?";
        $stmt_updatebio = $conn->prepare($sql);
        $stmt_updatebio->bind_param("ssss", $bio, $username, $name, $user_id);
        $stmt_updatebio->execute();
        
        if($stmt_updatebio->affected_rows > 0){
            $_SESSION['user_bio'] = $bio;
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $name;
            $_SESSION['editProfileSuccess'] = 'Profile updated successfully';
            header("Location: editProfile.php");
            exit;
        } else {
            $_SESSION['editProfileError'] = 'Cannot update profile';
        }
        $stmt_updatebio->close();
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include 'partials/_styles.php'?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
    .container-md {
        width: 50%;
    }

    @media (max-width: 768px) {
        .container-md {
            width: 100%;
        }
    }
    </style>
    <title>Edit Profile - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">

    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md">
        <?php 
        if(isset($_SESSION['editProfileError'])){
            echo '<div class="alert alert-danger alert-dismissible fade show mt-3 " role="alert">
        <strong>Sorry!</strong> '.$_SESSION['editProfileError'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['editProfileError']);
        }

        if(isset($_SESSION['editProfileSuccess'])){
            echo '<div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
            <strong>Success!</strong> '.$_SESSION['editProfileSuccess'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['editProfileSuccess']);  
        }
        if(isset($_SESSION['changePassMessage'])){
            echo '<div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
            <strong>Success!</strong> '.$_SESSION['changePassMessage'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['changePassMessage']);
        }
        else if(isset($_SESSION['changePassError'])){
            echo '<div class="alert alert-danger alert-dismissible fade show mt-3 " role="alert">
            <strong>Sorry!</strong> '.$_SESSION['changePassError'].'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['changePassError']);
        }
        ?>
        <div class="height mx-auto w-100 my-3">
            <h2>Edit your profile</h2>
            <form action="editProfile.php" method="post">
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea class="form-control" id="bio" name="bio" style="height: 100px; width: 300px"></textarea>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <p class="my-3">shubhamamlani104@gmail.com</p>
            </form>
            <hr>
            <a href="deleteaccount.php"><button class="btn btn-danger">Delete my
                    account</button></a>
            <a href="changepassword.php"><button class="btn btn-success">Change Password</button></a>
        </div>
    </main>
    <script>
    let bio = document.getElementById('bio');
    let name = document.getElementById('name')
    let username = document.getElementById('username');
    bio.value = `<?php echo $_SESSION['user_bio']?>`;
    name.value = `<?php echo $_SESSION['full_name']?>`;
    username.value = `<?php echo $_SESSION['username']?>`;
    </script>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>