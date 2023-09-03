<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php if (isset($_COOKIE["username"])):
    $name = $_COOKIE["username"];
?>
<div class="main">
    <h1><span class="user-name"><?= $name ?></span>. Это ваш личный кабинет </h1>

    <ul class="links">
        <li>
            <a href="/logout">Logout</a>
        </li>
    </ul>
</div>
<?php endif; ?>
</body>
</html>