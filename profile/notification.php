<?php
session_start();

include '../connection.php';


$user = $_GET['user'];

$sql = "SELECT * FROM Users WHERE username = '" . $user . "'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $email = $row['email'];
} else {
    header("Location: ../404.php");
}
if ($_SESSION['username'] != $username) {
    header("Location: ../403.php");
}

$sql = "SELECT * FROM notification WHERE userID = '" . $username . "' ORDER BY time DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" href="../favicon.ico">
    <title><?php echo $username ?>'s Notification</title>
</head>

<body>
    <main class="center-vertical-horizontal">
        <div class="container">
            <div class="row bg-white">
                <div class="panel panel-default" style="padding: 12px;">
                    <div class="panel-heading">
                        <a href="../index.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Go back home
                        </a>
                    </div>
                    <div class="panel-body">
                        <div class="d-flex justify-content-center">
                            <h1 class="panel-title">Your notification</h1>
                            </h1>
                        </div>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            echo "<ul class='list-group list-group-numbered'>You have " . mysqli_num_rows($result) . " notification(s)";
                     
                            while ($row = mysqli_fetch_assoc($result)) {
                                $checkMark = ($row['isRead'] == 1) ? '<i class="bi bi-check-lg"></i>' : '<i class="bi bi-x-lg"></i>';
                                $showBtnCheck = ($row['isRead'] == 0) ? '<input type="submit" class="btn btn-primary btn-sm" value="Mark read">' : '';
                                $link = ($row['link'] == '#') ? '#' : $root . $row['link'];

                                echo '
                                <li class="list-group-item d-flex justify-content-between align-items-start" id="no-before">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">' . $row['type'] . ' ' . $checkMark . '</div>
                                        <a href="' . $link . '">' . $row['details'] . '</a>
                                        <span class="badge bg-primary rounded-pill" id="notif-' . $row['id'] . '">' . $row['time'] . '</span>
                                    </div>
                                    <form action="./readNotif" method="POST">
                                        <input type="hidden" name="notifID" value="' . $row['id'] . '">
                                        ' . $showBtnCheck . '
                                    </form>
                                </li>';
                            }
                            echo "</ul>";
                        } else {
                            echo "You currently have no notification";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>