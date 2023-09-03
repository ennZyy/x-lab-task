<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-container">
    <h1>Register Form</h1>
    <p class="message-error"></p>
    <form method="post" action="/register-process" id="registerForm" class="form">
        <input type="text" name="username" placeholder="Enter your name">
        <input type="text" name="password" placeholder="Enter you password">
        <input type="submit" value="Register">
    </form>
</div>

<script src="../assets/js/main.js"></script>
</body>
</html>