<?php
session_start();

include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = $_POST['id'];
    $reason = $_POST['reason'];

    $sql = "SELECT * FROM post WHERE id = '" . $postID . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $postUser = $row['userID'];
    } else {
        echo 'Error: Post not found.';
    }

    if ($_SESSION['username'] != $postUser) {
        if (!isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] != 1) {
                header("Location: ../403.php");
                return;
            }
        }
    }

    $postID = strip_tags($postID);
    $postID = mysqli_real_escape_string($conn, $postID);

    $sql = "DELETE FROM post WHERE id = '$postID'";
    $result = mysqli_query($conn, $sql);

    if ($reason != '') {
        if ($_SESSION['isAdmin'] == 1) {
    
            $reason = strip_tags($reason);
            $reason = mysqli_real_escape_string($conn, $reason);

            $sql = "INSERT INTO notification (userID, link, type, details) VALUES ('$postUser', '#', 'Post Deleted By Admin', '$reason')";
            $result = mysqli_query($conn, $sql);
        }
    }

    if (!$result) {
        $error = 'Error: ' . mysqli_error($conn);
        echo $error;
    } else {
        echo 'success';
    }
} else {
    header("Location: ../403.php");
}