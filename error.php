<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'partials/_styles.php';?>
    <title>Error: Page Not Available</title>
    <style>
    .outer-container {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90.2vh;
    }

    .container {
        text-align: center;
    }

    h1 {
        color: #333;
    }

    p {
        color: #666;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="outer-container">
        <div class="container">
            <h1>Error: Page Not Available</h1>
            <p>The page you are trying to access is either private or does not exist.</p>
            <p>Please go back to the <a href="index.php">homepage</a> or try again later.</p>
        </div>
    </div>
    <?php include 'partials/_footer.php';?>
</body>

</html>