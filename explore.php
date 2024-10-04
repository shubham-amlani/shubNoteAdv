<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: index.php");
}
include 'partials/_dbconnect.php';
$sql_fetch_users = "SELECT * FROM `users`";
$stmt_fetch_users = $conn->prepare($sql_fetch_users);
$stmt_fetch_users->execute();
$result = $stmt_fetch_users->get_result();
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
    <title>Explore - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
        <div class="height py-md-5 px-2">
            <h1 class="py-3 px-2">
                People you can follow
            </h1>
            <div class="feed">
                <?php
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $user_id = $row['user_id'];
                            $username = $row['username'];
                            $name = $row['full_name'];
                            printUser($user_id, $username, $name, 'explore.php');
                            }
                    }
                ?>
            </div>
        </div>
    </main>
    <?php include 'partials/_uploadPost.php';?>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>