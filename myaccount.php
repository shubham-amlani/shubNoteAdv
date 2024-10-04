<?php 
session_start();
include 'partials/_sessionVars.php';
include 'partials/_dbconnect.php';
include 'partials/_functions.php';
if(!(isset($_SESSION['loggedin'])) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
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
    body {
        min-width: 100vw;
    }

    .container-md {
        width: 50%;
    }

    @media (max-width: 768px) {
        .container-md {
            width: 100%;
        }
    }
    </style>
    <title>Account - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <!-- --------------------------------------------Profile picture Modal-------------------------------------------- -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Profile picture</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="handlers/_handlePicture.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
                            <label for="image" class="my-1">Please choose a profile picture</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*"
                                required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" role="button" class="btn btn-primary">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- --------------------------------Profile pciture modal end ----------------------------------------------------- -->

    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md">
        <?php 
    if(isset($_SESSION['uploadSuccess']) && $_SESSION['uploadSuccess'] == true){
        echo '<div class="alert alert-success alert-dismissible fade show mt-3 " role="alert">
        <strong>Post Successful!</strong> You can view it in Your Posts section.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['uploadSuccess']);
    }
    else if(isset($_SESSION['uploadSuccess']) && $_SESSION['uploadSuccess'] == false){
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3 " role="alert">
        <strong>Post unsuccessful!</strong> Cannot upload post.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['uploadSuccess']);
    }
    ?>
        <div class="height mx-auto w-100">
            <div class="user-account">
                <hr>
                <div class="part-1 d-flex align-items-center">
                    <?php 
                    echo displayUserProfileImage($_SESSION['user_id']);
                    ?>
                    <div class="part1-2 d-flex flex-column ">
                        <span class="username"><?php echo $_SESSION['username'] ?></span>
                        <div class="btns">
                            <a href="editProfile.php"><button class="btn btn-success btn-sm ">Edit
                                    Profile <i class="fas fa-cog"></i></button></a>
                            <a href="partials/_logout.php"><button
                                    class="btn btn-outline-danger btn-sm ">Logout</i></button></a>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm my-2" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Upload profile picture
                        </button>

                    </div>
                </div>
                <hr>
                <div class="part-2 d-flex flex-column ">
                    <span class="username"><?php echo $_SESSION['full_name'] ?></span>
                    <p class="bio"><?php if(!empty($_SESSION['user_bio'])){
                        echo nl2br($_SESSION['user_bio']);
                    }
                    else{
                        echo "Click on edit profile to add your <span class='highlight'>bio</span> and <span class='highlight'>name</span>";
                    }?>
                    </p>
                </div>
                <hr class="mt-0">
                <div class="part-3 d-flex justify-content-around">
                    <div class="posts d-flex flex-column align-items-center ">
                        <b><?php echo numPosts($_SESSION['user_id']) ?></b>
                        <span>Posts</span>
                    </div>
                    <a href="followers.php?profileid=<?php echo $_SESSION['user_id']?>" class="td-none">
                        <div class="followers d-flex flex-column align-items-center ">
                            <b><?php echo numFollowers($_SESSION['user_id']) ?></b>
                            <span>Followers</span>
                        </div>
                    </a>
                    <a href="following.php" class="td-none">
                        <div class="following d-flex flex-column align-items-center ">
                            <b><?php echo numFollowing($_SESSION['user_id']) ?></b>
                            <span>Following</span>
                        </div>
                    </a>
                </div>
                <hr>
            </div>
            <div class="container user-posts px-0">
                <h3>Your Posts</h3>
                <?php 
                $sql = 'SELECT * FROM `posts` WHERE `post_user_id`=? ORDER BY `created` DESC';
                $stmt = $conn->prepare($sql);
                echo ($conn->error);
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $num_posts = $result->num_rows;

                if($num_posts > 0){
                    while($row = $result->fetch_assoc()){
                        $timestamp = strtotime($row['created']);
                        $formattedDate = date("jS M Y h:i A", $timestamp);
                        if(empty($row['enc_key'])){
                            $post_title = $row['post_title'];
                            $post_description = $row['post_description'];
                        } 
                        else{
                            $post_title = openssl_decrypt($row['post_title'], 'aes-256-cbc', $row['enc_key'], 0, $row['enc_iv']);
                            $post_description = openssl_decrypt($row['post_description'], 'aes-256-cbc', $row['enc_key'], 0, $row['enc_iv']);
                        }
                        printPost($row['post_id'], $user_id, $user_name, $formattedDate, $post_title, $post_description, $row['is_private']);
                    }
                }
                $stmt->close();
                ?>
                <hr>
            </div>
        </div>
        <?php include 'partials/_uploadPost.php';?>
    </main>

    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>