<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include 'partials/_functions.php'?>
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
    <title>Upload - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="mb-2 px-2 pb-1">
            <hr>
            <div class="d-flex align-items-center mb-2">
                <?php
            $img = displayUserImage($_SESSION['user_id']);
            echo $img;
            ?>
                <div class="user-details">
                    <h4 class="username m-0 "><?php echo $_SESSION['username'];?></h4>
                    <p class="m-0"><?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>
            <hr class="my-0">
        </div>
        <div class="height pb-3 pb-md-5 px-4">
            <h1 class="">Upload to <span class="highlight">shubNote</span></h1>
            <form action="handlers/_handleUpload.php" method="post">
                <div class="mb-3">
                    <label for="postTitle" class="form-label">Post title</label>
                    <input type="text" class="form-control" id="postTitle" name="postTitle">
                </div>
                <div class="mb-3">
                    <label for="postDescription" class="form-label">Post description</label>
                    <textarea class="form-control" id="postDescription" name="postDescription" style="height: 200px"
                        placeholder="Start expressing it freely..."></textarea>
                </div>
                <hr>
                <p>Select Visiblity - You can change it later</p>
                <select name="visiblity" id="visiblity" class="form-select">
                    <option value="select">Choose who can see this post</option>
                    <option value="public">Public (anyone can see)</option>
                    <option value="private">Private (only you can see)</option>
                </select>
                <button type="submit" class="btn btn-success mt-4">Post to shubNote</button>
            </form>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>