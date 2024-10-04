<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
}

$showFollowMessage = false;
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    include 'partials/_dbconnect.php';
    $user_id = $_GET['profileid'];
    if($_SESSION['user_id'] == $user_id){
        header("Location: myaccount.php");
    }
    $sql = "SELECT * FROM `users` WHERE `user_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $username = $row['username'];
            $user_bio = $row['user_bio'];
            $name = $row['full_name'];
        }
    }    
    else{
        header("Location: error.php");
        exit();
    }
}

if(isset($_SESSION['followMessage'])){
    $showFollowMessage = true;
    $followMessage = $_SESSION['followMessage'];
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
    <title>Profile - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md px-2">
        <div class="height mx-auto">
            <div class="user-account">
                <hr>
                <div class="part-1 d-flex align-items-center">
                    <?php 
                    echo displayUserProfileImage($user_id);
                    ?>
                    <div class="part1-2 d-flex flex-column ">
                        <span class="username"><?php echo $username; ?></span>
                        <form action="handlers/_handleFollowUnfollow.php" method="post">
                            <?php
                        echo '<input type="hidden" name="page" value="profile.php?profileid='.$user_id.'">';
                        if(checkFollow($_SESSION['user_id'], $user_id)){
                            echo '<input type="hidden" name="unfollower_user_id" value="'.$_SESSION['user_id'].'">
                            <input type="hidden" name="unfollowed_user_id" value="'.$user_id.'">
                            <button class="btn btn-outline-secondary py-0">Unfollow</button>';
                        }
                        else{
                            echo '<input type="hidden" name="follower_user_id" value="'.$_SESSION['user_id'].'">
                            <input type="hidden" name="followed_user_id" value="'.$user_id.'">
                            <button class="btn btn-primary">Follow</button>';
                        }
                        ?>
                        </form>
                        <a href="chat.php?profileid=<?php echo $user_id?>"><button class="btn btn-primary my-1 py-1 px-2">Message</button></a>
                    </div>
                </div>
                <hr>
                <div class="part-2 d-flex flex-column">
                    <span class="username"><?php echo $name; ?></span>
                    <p class="bio"><?php echo nl2br($user_bio); ?></p>
                </div>
                <hr class="mt-0">
                <div class="part-3 d-flex justify-content-around">
                    <div class="posts d-flex flex-column align-items-center ">
                        <b><?php echo numPublicPosts($user_id) ?></b>
                        <span>Public Posts</span>
                    </div>
                    <a href="followers.php?profileid=<?php echo $user_id?>" class="td-none">
                        <div class="followers d-flex flex-column align-items-center ">
                            <b><?php echo numFollowers($user_id) ?></b>
                            <span>Followers</span>
                        </div>
                    </a>
                        <div class="following d-flex flex-column align-items-center ">
                            <b><?php echo numFollowing($user_id) ?></b>
                            <span>Following</span>
                        </div>
                </div>
                <hr>
            </div>
            <div class="container user-posts">
                <h3>Public posts</h3>
                <?php
                $sql = "SELECT * FROM `posts` WHERE `post_user_id`=? AND `is_private`='0'  ORDER BY `created` DESC";
                $stmt = $conn->prepare($sql);
                echo($conn->error);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $num_posts = $result->num_rows;

                if($num_posts > 0){
                    while($row = $result->fetch_assoc()){
                        $timestamp = strtotime($row['created']);
                        $formattedDate = date("jS M Y h:i A", $timestamp);
                        printPost($row['post_id'], $row['post_user_id'], $username, $formattedDate, $row['post_title'], $row['post_description'], 0);
                    }
                }
                else{
                    echo '<div class="container bg-secondary-subtle p-3 my-3 mx-auto container">
                    <span class="fs-4">This user have no public posts.</span>
                    </div>';
                }
                $stmt->close();
                ?>
            </div>
            <hr>

        </div>
    </main>

    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>
</html>