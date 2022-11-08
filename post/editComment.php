<?php
session_start();

include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = $_GET['id'];


    $sql = "SELECT * FROM comment WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error = 'Error: ' . mysqli_error($conn);
        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
    }

    if (mysqli_num_rows($result) == 0) {
        header("Location: ../404.php");
    }

    $result = mysqli_fetch_assoc($result);
    $content = $result['content'];
    $user = $result['userID'];
    $postID = $result['postID'];


    if ($_SESSION['username'] != $user) {
        header("Location: ../403.php");
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $content = $_POST['content'];
    $id = $_POST['id'];
    $postID = $_POST['postID'];

    $sql = "SELECT userID FROM comment WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($result);
    $user = $result['userID'];

    if ($_SESSION['username'] != $user) {
        header("Location: ../403.php");
    }

    if ($content == '') {
        echo '<div class="alert alert-danger" role="alert">Content cannot be empty!</div>';
    } else {
    
        $content = strip_tags($content);

        $content = mysqli_real_escape_string($conn, $content);

        $sql = "UPDATE comment SET content = '$content' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            $error = 'Error: ' . mysqli_error($conn);
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        } else {
    
            echo '<script>alert("Comment updated successfully!");</script>';
    
            header("Location: ./?id=$postID#comment-$id");
        }
    }
}
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
    <title>Edit Comment</title>
</head>

<body>
    <main class="center-vertical-horizontal">
        <div class="container">
            <div class="row bg-white">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h1 class="panel-title" style="margin-top:5px;">Edit Comment</h1>
                        </div>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" name="content" id="content" rows="3" minlength="10"
                                    maxlength="1000" required><?php echo $content; ?></textarea>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="postID" value="<?php echo $postID; ?>">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" style="margin-top:5px;">Submit</button>
                                <a href="./?id=<?php echo $postID; ?>" class="btn btn-secondary"
                                    style="margin-top:5px;">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container">​</div>
            </div>
        </div>
    </main>
</body>

</html>