<?php
session_start();

include '../connection.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $notifID = $_POST['notifID'];


    $sql = "SELECT userID FROM notification WHERE id = '$notifID'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($result);
    $user = $result['userID'];

    if ($_SESSION['username'] != $user) {
        header("Location: ../403.php");
    }


    $sql = "UPDATE notification SET isRead = 1 WHERE id = '$notifID'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = 'Error: ' . mysqli_error($conn);
        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
    } else {
    
        header("Location: ./notification?user=$user#notif-$notifID");
    }
} else {
    header("Location: ../403.php");
}