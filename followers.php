<?php
session_start();
if(!isset($_GET['profileid'])){
    header("Location: home.php");
    exit();
}

else if($_SERVER['REQUEST_METHOD'] == 'GET'){
        include 'partials/_dbconnect.php';
        $profile_id = $_GET['profileid'];
        $sql = 'SELECT * FROM `followers` WHERE `followed_user_id`=?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $profile_id);
        $stmt->execute();
        $result = $stmt->get_result();
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
    <title>Followers - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="height py-md-5 px-2">
            <h1 class="py-3 px-2">
                Followers
            </h1>
            <div class="feed">
                <?php
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $sql_list = "SELECT * FROM `users` WHERE `user_id`=?";
                        $stmt_list = $conn->prepare($sql_list);
                        $stmt_list->bind_param('i', $row['follower_user_id']);
                        $stmt_list->execute();
                        $result_list = $stmt_list->get_result();
                        $row_list = $result_list->fetch_assoc();
                        $username = $row_list['username'];
                        $name = $row_list['full_name'];
                        $user_id = $row_list['user_id'];
                        printUser($user_id, $username, $name, 'followers.php?profileid='.$profile_id);
                    }
                }
                else{
                    echo '<div class="border border-secondary rounded p-4"><h3>You have no followers</h3></div>';
                }

                if($_SESSION['user_id'] == $profile_id){
                    echo '<div class="container mx-auto">Go back to <a href="myaccount.php">your account</a></div>';
                }
                else{
                    echo '<div class="container mx-auto">Go back to <a href="profile.php?profileid='.$profile_id.'">user profile</a></div>';
                }
                ?>
            </div>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>