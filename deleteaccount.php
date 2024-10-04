<?php
session_start();
include 'partials/_functions.php';
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("Location: login.php");
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
    <title>Delete your account - shubNote</title>
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-0 container-md p-0">
    <?php
    if(isset($_SESSION['deleteAccountError'])){
        echo '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
        <strong> Error!</strong> '.$_SESSION['deleteAccountError'].'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      unset($_SESSION['deleteAccountError']);
    }
    ?>
        <div class="container mx-auto my-4 border-secondary-subtle border-2 border rounded p-4">
            <h1 class="">Delete your account</h1>
            <p class="text fs-5">Are you sure you want to delete your account? Please note that this action is
                <b class="text-danger">irreversible</b>.
            </p>
            <p class="text fs-5">By deleting your account, all your account details, including follower information,
                posts,
                comments, and any other related data, will be <b class="text-danger">permanently removed</b> from our
                system. There will be no way to
                retrieve this information ever again.</p>
            <p class="text fs-5">If you are certain about deleting your account, please proceed. Otherwise, please go
                back.
            </p>
            <hr>
            <p class="text fs-5"><b>Before proceeding, please confirm the following:</b></p>
            <form action="handlers/_handleDeleteAccount.php" method="post">
                <div class="d-flex align-items-center gap-3 my-2">
                    <label for="consentCheck1" class="form-check-label">I have read and agree to the terms and
                        conditions.</label>
                    <input type="checkbox" name="consentCheck1" id="consentCheck1" class="form-check-input"
                        value="delete1">
                </div>
                <div class="d-flex align-items-center gap-3 my-2">
                    <label for="consentCheck2" class="form-check-label">I understand that deleting my account is
                        irreversible.</label>
                    <input type="checkbox" name="consentCheck2" id="consentCheck2" class="form-check-input"
                        value="delete2">
                </div>
                <div class="d-flex align-items-center gap-3 my-2">
                    <label for="consentCheck3" class="form-check-label">I acknowledge that <b
                            class="text-primary">all</b>
                        my
                        account data will be <b class="text-danger">permanently removed</b>.</label>
                    <input type="checkbox" name="consentCheck3" id="consentCheck3" class="form-check-input"
                        value="delete3">
                </div>
                <hr>
                <button class="btn btn-danger my-3" type="submit">Delete my account</button>
            </form>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <?php include 'partials/_scripts.php';?>
</body>

</html>