<?php

session_start();

// чек на залогиненного юзера
if (isset($_SESSION['email'])) {
    // удаляем сессию
    session_destroy();

    echo '<div class="alert alert-success" role="alert">Successfully Logged out, will redirect to main page in 3 seconds</div>';
    header('Location: ../index.php');
} else {
    header('location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../favicon.ico">
    <title>Logout</title>
</head>

<body>
</body>

</html>