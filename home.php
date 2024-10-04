<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
    exit();
}
include 'partials/_dbconnect.php';
$user_id = $_SESSION['user_id'];
$sql_followed_users = "SELECT `followed_user_id` FROM `followers` WHERE `follower_user_id`=?";
$stmt_followed_users = $conn->prepare($sql_followed_users);
$stmt_followed_users->bind_param('i', $user_id);
$stmt_followed_users->execute();
$result = $stmt_followed_users->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include 'partials/_functions.php'?>
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
    <title>Home - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="container height mx-auto w-100">
            <h1 class="p-3 m-0 my-0 my-md-3">
                Latest Posts by the people you follow
            </h1>
            <div class="feed">
            <?php
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $sql_display_posts = "SELECT * FROM `posts` WHERE `post_user_id`=? AND `is_private`=0";
                    $stmt_display_posts = $conn->prepare($sql_display_posts);
                    $stmt_display_posts->bind_param('i', $row['followed_user_id']);
                    $stmt_display_posts->execute();
                    $result_display_posts = $stmt_display_posts->get_result();
                if($result_display_posts->num_rows > 0){
                    while($row_display_posts = $result_display_posts->fetch_assoc()){
                        $post_id = $row_display_posts['post_id'];
                        $post_user_id = $row_display_posts['post_user_id'];
                        $post_title = $row_display_posts['post_title'];
                        $post_description = $row_display_posts['post_description'];
                        $timestamp = strtotime($row_display_posts['created']);
                        $formattedDate = date("jS M Y h:i A", $timestamp);

                        $sql_fetch_user = "SELECT `username` FROM `users` WHERE `user_id`=?";
                        $stmt_fetch_user = $conn->prepare($sql_fetch_user);
                        $stmt_fetch_user->bind_param('i', $row['followed_user_id']);
                        $stmt_fetch_user->execute();
                        $result_fetch_user = $stmt_fetch_user->get_result(); 
                        $row_fetch_user = $result_fetch_user->fetch_assoc();

                        $username = $row_fetch_user['username'];       
                        printPost($post_id, $post_user_id, $username, $formattedDate, $post_title, $post_description, 0);        
                    }
                }
                }
            }
            else{
                echo '<p class="p-4 bg-secondary-subtle fs-5">Discover and Connect: Start Following Others to Stay Updated! <a
                href="explore.php">Explore</a> here.</p>';
            }
            ?>

            </div>
        </div>
    </main>
    <?php include 'partials/_uploadPost.php';?>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>