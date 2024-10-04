<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include '../partials/_dbconnect.php';
    $post_id = $_POST['postid'];
    $sql = 'DELETE FROM `posts` WHERE `posts`.`post_id`=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        header("Location: ../myaccount.php");
    }
}
?>