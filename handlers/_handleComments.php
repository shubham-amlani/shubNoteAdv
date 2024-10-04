<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $comment_user_id = $_POST['user_id'];
    $comment_post_id = $_POST['post_id'];
    $comment_content = $_POST['comment_content'];

    include '../partials/_dbconnect.php';
    $sql_insert_comment = "INSERT INTO `comments` (`comment_id`, `comment_user_id`, `comment_post_id`, `comment_content`, `timestamp`) VALUES (NULL, ?, ?, ?, current_timestamp())";
    $stmt_insert_comment = $conn->prepare($sql_insert_comment);
    $stmt_insert_comment->bind_param("iis", $comment_user_id, $comment_post_id, $comment_content);
    $stmt_insert_comment->execute();
    if($stmt_insert_comment->affected_rows > 0){
        $_SESSION['commentSuccess'] = true;
        header("Location: ../post.php?postid=$comment_post_id");
        $stmt_insert_comment->close();
        exit();
    }
    else{
        $_SESSION['commentSuccess'] = false;
        header("Location: ../post.php?postid=$comment_post_id");
        $stmt_insert_comment->close();
        exit();
    }
}
?>