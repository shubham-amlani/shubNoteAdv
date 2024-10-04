<?php
session_start();
include 'partials/_functions.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    include 'partials/_dbconnect.php';
    include 'partials/_sessionVars.php';
    $post_id = $_GET['postid'];
    $sql = "SELECT * FROM `posts` WHERE `post_id`=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $timestamp = strtotime($row['created']);
            $formattedDate = date("jS M Y h:i A", $timestamp);
            $post_title = $row['post_title'];
            $post_description = $row['post_description'];
            $post_user_id = $row['post_user_id'];
            $sql_getuser = "SELECT * FROM `users` WHERE `user_id`=?";
            $stmt_getuser = $conn->prepare($sql_getuser);
            $stmt_getuser->bind_param('i', $post_user_id);
            $stmt_getuser->execute();
            $result_getuser = $stmt_getuser->get_result();
            $row_getuser = $result_getuser->fetch_assoc();
            $post_username = $row_getuser['username'];  
            $is_private = $row['is_private'];
            $key = $row['enc_key'];
            $iv = $row['enc_iv'];
            if($is_private == '1'){
                $visiblity = 'private';
                if(!isset($_SESSION['loggedin'])){
                    header("Location: index.php");
                }
                else{
                    if($_SESSION['user_id'] != $post_user_id){
                        header("Location: error.php");
                    }
                }
            }
            else{
                $visiblity = 'public';
            }
        }
    }
    else{
        header("Location: error.php");
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
    <title>Edit Post - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="height py-3 py-md-5 px-4">
            <h1 class="">Edit this post</h1>
            <form action="handlers/_handleEditPost.php" method="post">
                <input type="hidden" name="postid" value="<?php echo $post_id?>">
                <div class="mb-3">
                    <label for="postTitle" class="form-label">Post title</label>
                    <input type="text" class="form-control" id="postTitle" name="postTitle">
                </div>
                <div class="mb-3">
                    <label for="postDescription" class="form-label">Post description</label>
                    <textarea class="form-control" id="postDescription" name="postDescription" style="height: 400px"
                        placeholder="Start expressing it freely..."></textarea>
                </div>
                <hr>
                <p>Select Visiblity - You can change it later</p>
                <select name="visiblity" id="visiblity" class="form-select">
                    <option value="select">Choose who can see this post</option>
                    <option value="public" <?php if($visiblity == 'public'){
                    echo 'selected';
                }?>>Public (anyone can see)</option>
                    <option value="private" <?php if($visiblity == 'private'){
                    echo 'selected';
                }?>>Private (only you can see)</option>
                </select>
                <button type="submit" class="btn btn-success mt-4">Post to shubNote</button>
            </form>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
    <script>
        <?php 
    if($visiblity == 'private'){
        $post_title = htmlspecialchars_decode(openssl_decrypt($post_title, 'aes-256-cbc', $key, 0, $iv));
        $post_description = htmlspecialchars_decode(openssl_decrypt($post_description, 'aes-256-cbc', $key, 0, $iv));
    }
    else{
        $post_title = htmlspecialchars_decode($post_title);
        $post_description = htmlspecialchars_decode($post_description);
    }
    ?>
    let postTitle = document.getElementById('postTitle');
    let postDescription = document.getElementById('postDescription');
    let postTitleValue =<?php echo '`'.$post_title.'`' ?>;
    postTitle.value = postTitleValue;
    postDescription.value = <?php echo '`'.$post_description.'`' ?>;
    </script>
</body>

</html>