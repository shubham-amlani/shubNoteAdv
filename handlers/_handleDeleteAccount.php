<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION!=true){
    header("Location: ../index.php");
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $consentCheck1 = '0';
    $consentCheck2 = '0';
    $consentCheck3 = '0';
    if(isset($_POST['consentCheck1'])){
        $consentCheck1 = $_POST['consentCheck1'];
    }
    if(isset($_POST['consentCheck2'])){
        $consentCheck2 = $_POST['consentCheck2'];
    }
    if(isset($_POST['consentCheck3'])){
        $consentCheck3 = $_POST['consentCheck3'];
    }
    
    if($consentCheck1 == 'delete1' && $consentCheck2 == 'delete2' && $consentCheck3 == 'delete3'){
        include '../partials/_dbconnect.php';
        $sql_comments = 'DELETE FROM `comments` WHERE `comment_user_id`=?';
        $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->bind_param("i", $_SESSION['user_id']);
        $stmt_comments->execute();
        $comment_affected_rows = $stmt_comments->affected_rows;

        $sql_posts = 'DELETE FROM `posts` WHERE `post_user_id`=?';
        $stmt_posts = $conn->prepare($sql_posts);
        $stmt_posts->bind_param("i", $_SESSION['user_id']);
        $stmt_posts->execute();
        $post_affected_rows = $stmt_posts->affected_rows;

        $sql_followers = 'DELETE FROM `followers` WHERE `follower_user_id`=? OR `followed_user_id`=?';
        $stmt_followers = $conn->prepare($sql_followers);
        $stmt_followers->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
        $stmt_followers->execute();
        $followers_affected_rows = $stmt_followers->affected_rows;

        $sql_users = 'DELETE FROM `users` WHERE `user_id`=?';
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("i", $_SESSION['user_id']);
        $stmt_users->execute();
        $users_affected_rows = $stmt_users->affected_rows;
        
        header("Location: ../partials/_logout.php");
    }
    else if($consentCheck1 == '0' || $consentCheck2 == '0' || $consentCheck3 == '0'){
        $_SESSION['deleteAccountError'] = "Check all three boxes to delete your account";
        header("Location: ../deleteaccount.php");
        exit();
    }
}
?>