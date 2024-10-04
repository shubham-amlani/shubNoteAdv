<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    include 'partials/_dbconnect.php';
    if(isset($_GET['search'])){
        $query = $_GET['search'];
    
        $sql_users = "SELECT * FROM `users` WHERE MATCH (`username`) AGAINST (?)";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("s", $query);
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();
    
        $sql_posts = "SELECT * FROM `posts` WHERE MATCH (`post_title`, `post_description`) AGAINST (?) AND `is_private`=0";
        $stmt_posts = $conn->prepare($sql_posts);
        $stmt_posts->bind_param("s", $query);
        $stmt_posts->execute();
        $result_posts = $stmt_posts->get_result();
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
    <title>Search - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="height py-md-5 px-2">
            <h2 class="my-3">Search <span class="highlight">shubNote</span></h2>
            <div>
                <form action="search.php"
                    class="mb-3 border-secondary-subtle border rounded-5 d-flex align-items-center">
                    <input type="search" class="form-control mx-3 border-0" id="search" name="search">
                    <button class="nav-link" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <?php 
            if(isset($_GET['search'])){
                echo '<h2>Users</h2>';
                if($result_users->num_rows > 0){
                    while($row_users = $result_users->fetch_assoc()){
                        $user_id = $row_users['user_id'];
                        $username = $row_users['username'];
                        $name = $row_users['full_name'];
                        printUser($user_id, $username, $name, 'search.php?search='.$query);
                    }
                }
                else{
                    echo '<div class="bg-secondary-subtle p-3 my-3 mx-auto container">
            <span class="fs-4">No search results</span>
            </div>';
                }
            }
                if(isset($_GET['search'])){
                    echo '<h2>Posts</h2>
                    <div class="postResults">';
                    if($result_posts->num_rows > 0){
                        while($row_posts = $result_posts->fetch_assoc()){
                            $sql = "SELECT `username` FROM `users` WHERE `user_id`=?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $row_posts['post_user_id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
    
                            $username = $row['username'];
                            $userid = $row_posts['post_user_id'];
                            $post_title = $row_posts['post_title'];
                            $post_description = $row_posts['post_description'];
                            $post_time = strtotime($row_posts['created']);
                            $post_id = $row_posts['post_id'];
    
                            $formattedDate = date("jS M Y h:i A", $post_time);
                            printPost($post_id, $userid, $username, $formattedDate, $post_title, $post_description, 0);
                        }
                    }
                    else{
                        echo '<div class="bg-secondary-subtle p-3 my-3 mx-auto container">
                        <span class="fs-4">No search results</span>
                         </div>';
                    }
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