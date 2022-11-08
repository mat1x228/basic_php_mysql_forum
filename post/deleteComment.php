<?php

session_start();

include '../connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $commentID = $_POST['id'];
    $reason = $_POST['reason'];

    $sql = "SELECT * FROM comment WHERE id = '" . $commentID . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $commentUser = $row['userID'];
        $postID = $row['postID'];
    } else {
        echo 'Error: Comment not found.';
    }

    if ($_SESSION['username'] != $commentUser) {
        if (!isset($_SESSION['isAdmin'])) {
            if ($_SESSION['isAdmin'] != 1) {
                header("Location: ../403.php");
                return;
            }
        }
    }

    $commentID = strip_tags($commentID);
    $commentID = mysqli_real_escape_string($conn, $commentID);

    $sql = "DELETE FROM comment WHERE id = '$commentID'";
    $result = mysqli_query($conn, $sql);

    if ($reason != '') {
  
        if ($_SESSION['isAdmin'] == 1) {
   
            $reason = strip_tags($reason);
            $reason = mysqli_real_escape_string($conn, $reason);

            $sql = "INSERT INTO notification (userID, link, type, details) VALUES ('$commentUser', '#', 'Comment Deleted By Admin', '$reason')";
            $result = mysqli_query($conn, $sql);
        }
    }

    if (!$result) {
        $error = 'Error: ' . mysqli_error($conn);
        echo $error;
    } else {
        if ($reason != '') {
            echo 'success';
        } else {
            header("Location: ../post?id=" . $postID);
        }
    }
} else {
    header("Location: ../403.php");
}