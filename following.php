<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
    exit();
}
else if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    include 'partials/_dbconnect.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM `followers` WHERE `follower_user_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
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
    <title>Following - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="height py-md-5 px-2">
            <h1 class="py-3 px-2">
                Following
            </h1>
            <div class="feed">
                <?php
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $sql_list = "SELECT * FROM `users` WHERE `user_id`=?";
                    $stmt_list = $conn->prepare($sql_list);
                    $stmt_list->bind_param('i', $row['followed_user_id']);
                    $stmt_list->execute();
                    $result_list = $stmt_list->get_result();
                    $row_list = $result_list->fetch_assoc();
                    $username = $row_list['username'];
                    $user_id = $row_list['user_id'];
                    $name = $row_list['full_name'];
                    printUser($user_id, $username, $name, 'following.php');
                }
            }
            else{
                echo '<p class="rounded p-4 bg-secondary-subtle fs-5">Discover and Connect: Start Following Others to Stay Updated! <a
                href="explore.php">Explore</a> here.</p>';
            }
        ?>
                <div class="container mx-auto">Go back to <a href="myaccount.php">your account</a></div>
            </div>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>