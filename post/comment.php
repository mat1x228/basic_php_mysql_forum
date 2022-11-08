<?php


session_start();


include '../connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $content = $_POST['comment'];
    $postID = $_POST['postID'];

    echo $content;
    echo $postID;


    if (isset($_SESSION['username'])) {

        $user = $_SESSION['username'];


        $content = strip_tags($content);
        $content = mysqli_real_escape_string($conn, $content);

        $postID = strip_tags($postID);
        $postID = mysqli_real_escape_string($conn, $postID);


        $sql = "INSERT INTO comment (content, userID, postID) VALUES ('$content', '$user', '$postID')";
        $result = mysqli_query($conn, $sql);

        $id = mysqli_insert_id($conn);

        if (!$result) {
            $error = 'Error: ' . mysqli_error($conn);
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }

        $sql = "SELECT userID, title FROM post WHERE id = '$postID'";
        $result = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($result);
        $owner = $result['userID'];
        $title = $result['title'];

        if ($user != $owner) {
            $details = "A user commented on your post titled \"$title\"";
            $link = "post/index?id=$postID#comment-$id";
            $sql = "INSERT INTO notification (userID, link, type, details) VALUES ('$owner', '$link', 'Post Comment', '$details')";
            $result = mysqli_query($conn, $sql);
    
            if (!$result) {
                $error = 'Error: ' . mysqli_error($conn);
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }

        $regex = '/@([A-Za-z0-9_]+)/';

        preg_match_all($regex, $content, $matches);


        $matches = array_unique($matches[0]);

        foreach ($matches as $user) {
     
            $user = trim($user);
      
            $user = str_replace('@', '', $user);

            if ($_SESSION['username'] != $user) {
                $details = "A user mentioned you in a comment on a post titled \"$title\"";
                $link = "post/index?id=$postID#comment-$id";

                $sql = "SELECT * FROM users WHERE username = '$user'";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    $error = 'Error: ' . mysqli_error($conn);
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }

                if ($result) {

                    $sql = "INSERT INTO notification (userID, link, type, details) VALUES ('$user', '$link', 'Comment Mention', '$details')";
                    $result = mysqli_query($conn, $sql);
        
                    if (!$result) {
                        $error = 'Error: ' . mysqli_error($conn);
                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                    }
                }
            }
        }
    
        header('Location: ./index?id=' . $postID);
    } else {
        header("Location: ../403.php");
    }
}