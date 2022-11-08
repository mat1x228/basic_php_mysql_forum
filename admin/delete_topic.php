<?php

session_start();

// чек админа
if (!isset($_SESSION['isAdmin'])) {
    header("Location: ../403.php");
} else {
    if ($_SESSION['isAdmin'] == 0) {
        header("Location: ../403.php");
    }
}

// бэ
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];

    
    $id = strip_tags($id);
    $id = mysqli_real_escape_string($conn, $id);

    // удаление из бд
    $sql = "DELETE FROM topic WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    // чек
    if (!$result) {
        $error = 'Error: ' . mysqli_error($conn);
        echo $error;
    } else {
        echo 'success';
    }
} else {
    header("Location: ../403.php");
}