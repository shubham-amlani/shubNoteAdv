<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    session_start();
    include '../partials/_sessionVars.php';
    include '../partials/_dbconnect.php';
    include '../partials/_validateInput.php';
    $post_title = validateInput($_POST['postTitle']);
    $post_description = validateInput($_POST['postDescription']);
    $visiblity = $_POST['visiblity'];
    $post_user_id = $user_id;
    $key = '';
    $iv = '';

    if($post_title==NULL || $post_description==NULL){
        $_SESSION['postError'] = "Title or description cannot be empty";
        header("Location: ../upload.php");
    }
    else{
        if($visiblity == 'select'){
            $_SESSION['postError'] = "Please select your post visiblity";
            header("Location: ../upload.php");
        }
        else{
            if($visiblity=='public'){
                $is_private = 0;
            }
            else if($visiblity=='private'){
                $is_private = 1;
                $key = openssl_random_pseudo_bytes(32);
                $iv = openssl_random_pseudo_bytes(16);
                $post_description = openssl_encrypt($post_description, 'aes-256-cbc', $key, 0, $iv);
                $post_title = openssl_encrypt($post_title, 'aes-256-cbc', $key, 0, $iv);
            }

            $sql = "INSERT INTO `posts` (`post_id`, `post_user_id`, `post_title`, `post_description`, `is_private`, `created`, `enc_key`, `enc_iv`) VALUES (NULL, ?, ?, ?, ?, current_timestamp(), ?, ?)";
            $stmt_post = $conn->prepare($sql);
            $stmt_post->bind_param("ississ", $user_id, $post_title, $post_description, $is_private, $key, $iv);
            $stmt_post->execute();
            if($stmt_post->affected_rows > 0){
                $_SESSION['uploadSuccess'] = true;
                header("Location: ../myaccount.php");
            }
        }
    }
}
?>