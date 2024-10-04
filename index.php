<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to shubNote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/_closedTemplate.css">
    <link rel="stylesheet" href="css/_utils.css">
    <link rel="stylesheet" href="css/_custom.css">
    <style>
    body {
        font-family: 'Ubuntu', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        text-align: center;
        margin-top: 50px;
    }

    .header h1 {
        font-size: 48px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
    }

    .header p {
        font-size: 18px;
        font-weight: 400;
        color: #666;
        margin-bottom: 40px;
    }

    .btn-container {
        display: flex;
        justify-content: center;
    }

    .btn {
        font-size: 18px;
        font-weight: 500;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 15px 40px;
        margin: 0 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-c {
        display: inline-block;
        padding: 8px 20px;
        margin: 10px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-login {
        background-color: #4caf50;
    }

    .btn-signup {
        background-color: #4caf50;
    }

    .btn:hover {
        opacity: 0.8;
    }

    .features {
        margin-top: 50px;
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .feature {
        text-align: center;
        max-width: 300px;
    }

    .feature-icon {
        font-size: 36px;
        color: #007bff;
        margin-bottom: 20px;
    }

    .feature-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .feature-description {
        font-size: 18px;
        font-weight: 400;
        color: #666;
    }

    .footer {
        background-color: #333;
        color: #fff;
        padding: 50px 0;
        text-align: center;
    }

    .footer p {
        margin: 0;
        font-size: 16px;
    }

    .footer a {
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="container text-center">
            <h1>[ Welcome to <span class="highlight">shubNote</span> ]</h1>
            <p>It's free to join. Signup below and get started.</p>
            <div>
                <a href="login.php" class="btn-c btn-login">Login</a>
                <a href="signup.php" class="btn-c">Signup</a>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="features">
            <div class="feature">
                <div class="feature-icon">📝</div>
                <div class="feature-title">Organize</div>
                <div class="feature-description">Effortlessly organize your notes, tasks, and ideas in one place.</div>
            </div>
            <div class="feature">
                <div class="feature-icon">🔒</div>
                <div class="feature-title">Secure</div>
                <div class="feature-description">Your data is encrypted and secure, ensuring privacy and
                    confidentiality.</div>
            </div>
            <div class="feature">
                <div class="feature-icon">🌐</div>
                <div class="feature-title">Accessible</div>
                <div class="feature-description">Access your notes anytime, anywhere, from any device.</div>
            </div>
        </div>
    </div>

    <?php include 'partials/_footer.php'?>
</body>

</html>