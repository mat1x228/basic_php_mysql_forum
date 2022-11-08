<?php
$DEBUG = false;

$conn = mysqli_connect('localhost', 'test2' , '123qwe', 'db_forum');


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
