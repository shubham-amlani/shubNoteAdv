<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $comment_id = $_POST['comment_id'];
    $comment_content = $_POST['editComment'];
    $post_id = $_POST['post_id'];
    include '../partials/_dbconnect.php';
    $sql_edit_comment = "UPDATE `comments` SET `comment_content`=? WHERE `comment_id`=?";
    $stmt_edit_comment = $conn->prepare($sql_edit_comment);
    $stmt_edit_comment->bind_param('si', $comment_content, $comment_id);
    $stmt_edit_comment->execute();
    if($stmt_edit_comment->affected_rows > 0){
        header("Location: ../post.php?postid=$post_id");
    }
    else{
        $_SESSION['editCommentError'] = "Cannot edit the comment";
        header("Location: ../post.php?postid=$post_id");
    }
}
?>