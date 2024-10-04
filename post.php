<?php
session_start();
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
            if($is_private == '1'){
                if(!isset($_SESSION['loggedin'])){
                    header("Location: index.php");
                }
                else{
                    if($_SESSION['user_id'] != $post_user_id){
                        header("Location: error.php");
                        exit();
                    }
                }
                $post_title = openssl_decrypt($row['post_title'], 'aes-256-cbc', $row['enc_key'], 0, $row['enc_iv']);
                $post_description = openssl_decrypt($row['post_description'], 'aes-256-cbc', $row['enc_key'], 0, $row['enc_iv']);
            }
        }
    }
    else{
        header("Location: error.php");
        exit();
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
    <title>Post - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md">
        <?php
    if(isset($_SESSION['commentSuccess']) && $_SESSION['commentSuccess'] == true){
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>Success! </strong> Comment Added
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['commentSuccess']);
    }

    else if(isset($_SESSION['commentSuccess']) && $_SESSION['commentSuccess'] == false){
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3 " role="alert">
        <strong>Sorry! </strong> Comment cannot be added
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['commentSuccess']);
    }

    if(isset($_SESSION['deleteCommentSuccess']) && $_SESSION['deleteCommentSuccess'] == false){
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3 " role="alert">
        <strong>Sorry! </strong> Comment cannot be deleted
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['deleteCommentSuccess']);
    }
    else if(isset($_SESSION['deleteCommentSuccess']) && $_SESSION['deleteCommentSuccess'] == true){
        echo '<div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
        <strong>Success! </strong> Comment deleted successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['deleteCommentSuccess']);
    }

    if(isset($_SESSION['editSuccess']) && $_SESSION['editSuccess'] == false){
        echo '<div class="alert alert-warning alert-dismissible fade show mt-3 " role="alert">
        <strong>Alert! </strong> No changes made to post
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['editSuccess']);
    }
    else if(isset($_SESSION['editSuccess']) && $_SESSION['editSuccess'] == true){
        echo '<div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
        <strong>Success! </strong> Post edited successfully
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['editSuccess']);
    }
    ?>
        <!-- Delete Post Modal -->
        <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete post</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this post?
                    </div>
                    <div class="modal-footer">
                        <form action="handlers/_handleDelete.php" method="post">
                            <input type="hidden" name="postid" value="<?php echo $post_id;?>">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" role="button" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="height mx-auto w-100">
            <div class="post d-flex flex-column my-5">
                <div class="upper-post d-flex">
                    <?php echo displayUserImage($post_user_id);?>
                    <div class="upper-post-right d-flex flex-column">
                    <a class="nav-link m-0 p-0" href="profile.php?profileid=<?php echo $post_user_id?>"><span class="username"><?php echo $post_username;?></span></a>
                        <span class="post-date"><?php echo $formattedDate;?></span>
                        <?php 
                        if($is_private == 1){
                            echo '<span class="private-post-indicator rounded">(Private)</span>';
                        }
                        if($post_user_id == $_SESSION['user_id']){
                            echo '<div class="btns my-2">
                            <a href="editpost.php?postid='.$post_id.'"><button class="btn btn-primary">Edit</button></a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal">
                                Delete
                            </button>
                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="post-lower">
                <div class="lower-post-up d-flex flex-column">
                    <b class="post-title"><?php echo $post_title;?></b>
                    <hr>
                    <span class="post-description"><?php echo nl2br(makeLinksClickable($post_description));?><br>
                    </span>
                    <hr>
                </div>
            </div>
            <?php
            if($is_private == 0){
                echo '<div class="comments">
                <form action="handlers/_handleComments.php" method="post">
                    <div class="mb-3">
                        <input type="hidden" name="post_id" value="'.$post_id.'">
                        <input type="hidden" name="user_id" value="'.$_SESSION['user_id'].'">
                        <label for="comment_content" class="form-label">Post a comment</label>
                        <textarea class="form-control" id="comment_content" name="comment_content" style="height: 100px"
                            placeholder="Type your comment here..."></textarea>
                    </div>
                    <button class="btn btn-success" type="submit">Post comment</button>
                </form>
                <hr>
            </div>
            <h2>Comments</h2>';
            $sql_comments = "SELECT * FROM `comments` WHERE `comment_post_id`=?";
            $stmt_comments = $conn->prepare($sql_comments);
            $stmt_comments->bind_param('i', $post_id);
            $stmt_comments->execute();
            $result_comments = $stmt_comments->get_result();
            if($result_comments->num_rows > 0){
                while($row_comments = $result_comments->fetch_assoc()){
                    $comment_id = $row_comments['comment_id'];
                    $comment_user_id = $row_comments['comment_user_id'];
                    $comment_content = $row_comments['comment_content'];
                    $comment_time = strtotime($row_comments['timestamp']);
                    $formattedDate = date("jS M Y h:i A", $comment_time);

                    $sql_fetch_user = "SELECT * FROM `users` WHERE `user_id`=?";
                    $stmt_fetch_user = $conn->prepare($sql_fetch_user);
                    $stmt_fetch_user->bind_param("i", $comment_user_id);
                    $stmt_fetch_user->execute();
                    $result_fetch_uesr = $stmt_fetch_user->get_result();
                    $row_fetch_user = $result_fetch_uesr->fetch_assoc();
                    $comment_user_name = $row_fetch_user['username'];

                    printComment($comment_id, $comment_user_id, $comment_user_name, $formattedDate, $comment_content);
            }
        }
        else{
            echo '<div class="bg-secondary-subtle p-3 my-3 mx-auto container">
            <span class="fs-4">No comments here. Be the first person to post a comment.</span>
            </div>';
        }
            }
            ?>
            <!-- Edit Comment Modal -->
            <div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit this comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="handlers/_handleEditComment.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post_id?>">
                                <input type="hidden" name="comment_id" id="comment_id">
                                <textarea class="form-control" id="editComment" name="editComment" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" role="button" class="btn btn-primary">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Comment Modal -->
            <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete this comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this comment?</p>
                            <form action="handlers/_handleDeleteComment.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post_id?>">
                                <input type="hidden" name="comment_id" id="delete_comment_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" role="button" class="btn btn-danger">Delete</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
    let editBtns = document.getElementsByClassName('editCommentBtn');
    let editComment = document.getElementById('editComment');
    let commentId = document.getElementById('comment_id');
    let deleteCommentId = document.getElementById('delete_comment_id');
    let deleteBtns = document.getElementsByClassName('deleteCommentBtn');
    console.log(editBtns);
    console.log(deleteBtns);
    if (editBtns) {
        let editBtnsArray = Array.from(editBtns);
        editBtnsArray.forEach(function(button) {
            button.addEventListener('click', function(e) {
                editComment.value = e.target.parentNode.parentNode.parentNode.nextElementSibling
                    .innerText;
                commentId.value = e.target.parentNode.parentNode.parentNode.nextElementSibling
                    .getAttribute('data-commentid');
            });
        });
    }

    if (deleteBtns) {
        let deleteBtnsArray = Array.from(deleteBtns);
        deleteBtnsArray.forEach(function(button) {
            button.addEventListener('click', function(e) {
                deleteCommentId.value = e.target.parentNode.parentNode.parentNode.nextElementSibling
                    .getAttribute('data-commentid');
            });
        });
    }
    </script>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>