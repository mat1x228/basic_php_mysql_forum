<!DOCTYPE html>
<html lang="en">

<?php

session_start();


if (!isset($_SESSION['isAdmin'])) {
    header("Location: ../403.php");
} else {
    if ($_SESSION['isAdmin'] == 0) {
        header("Location: ../403.php");
    }
}

?>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="../index.css">
    <link rel="icon" href="../favicon.ico">
    <title>Post Tags</title>
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
                        <a href="./add_topic.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus"></i> Add new tag
                        </a>
                    </div>
                    <div class="panel-body">
                    
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Topic</th>
                                    <th scope="col">Posts</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                         
                                include '../connection.php';

                                // получение всех топиков
                                $sql = "SELECT * FROM topic";
                                $result = mysqli_query($conn, $sql);
                               
                                if (!$result) {
                                    
                                    echo mysqli_error($conn);
                                }

                                
                                $resultCheck = mysqli_num_rows($result);

                              
                                if ($resultCheck > 0) {
                      
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $tag = $row['name'];
                                        $tag_id = $row['id'];
                                        $sql = "SELECT * FROM post WHERE topicID = '" . $tag_id . "'";
                                        $result2 = mysqli_query($conn, $sql);
                                        $num_posts = mysqli_num_rows($result2);
                                        echo '<tr>';
                                        echo '<th scope="row">' . $tag_id . '</th>';
                                        echo '<td>' . $tag . '</td>';
                                        echo '<td>' . $num_posts . '</td>';
                                        echo '<td><a href="edit_topic.php?id=' . $tag_id . '" class="btn btn-primary btn-sm">Edit</a> <a onclick="deleteTag(' . $tag_id . ')" class="btn btn-danger btn-sm">Delete</a></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr>';
                                    echo '<th scope="row">0</th>';
                                    echo '<td>No topic</td>';
                                    echo '<td>0</td>';
                                    echo '<td><a href="./add_topic.php" class="btn btn-primary btn-sm">Add</a></td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                            <script>
                            function deleteTag(id) {
                                if (confirm("Are you sure you want to delete this topic?")) {
                                    $.ajax({
                                        url: "./delete_topic.php",
                                        method: "POST",
                                        data: {
                                            id: id
                                        },
                                        success: function(data) {
                                            if (data == "success") {
                                                location.reload();
                                            } else {
                                                alert(data);
                                            }
                                        }
                                    });
                                }
                            }
                            </script>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>