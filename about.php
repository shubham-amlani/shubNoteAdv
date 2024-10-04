<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About - shubNote</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <?php include 'partials/_styles.php';?>
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
</head>

<body class="d-flex align-items-center justify-content-center">
    <?php include 'partials/_functions.php'; ?>
    <?php include 'partials/_sidebar.php'; ?>
    <main class="main mx-2 container-md p-2">
        <div class="height py-md-5 px-2">
            <h1 class="my-2">Frequently Asked Questions</h1>
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is ShubNote and how does it work?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>ShubNote is a versatile platform designed for creating, sharing, and organizing
                                notes and ideas. It provides users with a user-friendly interface to jot down thoughts,
                                organize content, and collaborate with others seamlessly.</code>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Is ShubNote free to use?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Yes, ShubNote is completely free to use.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How are my private posts secured on ShubNote?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Rest assured that your private posts are kept confidential and cannot be read by anyone
                            else.<br><br>

                            ShubNote employs AES-256-bit encryption, a widely recognized and highly secure encryption
                            standard, to protect your private posts. When you create a private post on ShubNote, the
                            content, including the post title and description, is encrypted using the AES-256 algorithm
                            before being stored in our database.<br><br>

                            AES-256, which stands for Advanced Encryption Standard with a key length of 256 bits, is one
                            of the most secure encryption algorithms available today. It utilizes a symmetric encryption
                            approach, meaning the same key is used for both encryption and decryption.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'partials/_bottomNav.php';?>
    <script></script>
    <?php include 'partials/_scripts.php' ?>
</body>

</html>