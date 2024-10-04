<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $comment_id = $_POST['comment_id'];
    $post_id = $_POST['post_id'];
    include '../partials/_dbconnect.php';
    $sql_edit_comment = "DELETE FROM `comments` WHERE `comments`.`comment_id`=?";
    $stmt_edit_comment = $conn->prepare($sql_edit_comment);
    $stmt_edit_comment->bind_param('i', $comment_id);
    $stmt_edit_comment->execute();
    if($stmt_edit_comment->affected_rows > 0){
        $_SESSION['deleteCommentSuccess'] = true;
        header("Location: ../post.php?postid=$post_id");
    }
    else{
        $_SESSION['deleteCommentSuccess'] = false;
        header("Location: ../post.php?postid=$post_id");
    }
}
?>