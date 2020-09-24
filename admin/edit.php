<?php

session_start();
require '../config/config.php';
include 'header.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

if ($_POST) {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];

  if ($_FILES['image']['name'] != null) {
    $file = 'images/'.($_FILES['image']['name']);
    $imgType = pathinfo($file,PATHINFO_EXTENSION);

    if ($imgType != 'png' && $imgType != 'jpg' && $imgType != 'jpeg') {
      echo "<script>alert('Image must be png,jpg,jpeg')</script>";
    } else {
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);

      $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id' ");
      $result = $stmt->execute();

      if ($result) {
        echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
      }
   } 
  } else {
    $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id' ");
      $result = $stmt->execute();

      if ($result) {
        echo "<script>alert('Successfully Updated');window.location.href='index.php';</script>";
      }
  }
}

// edit 
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();

$result = $stmt->fetchAll();
// print_r($results);

?>


    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                  <label>Title</label>
                  <input type="text" class="form-control" name="title" value="<?= $result[0]['title'] ?>" required>
                </div>
                <div class="form-group">
                  <label>Content</label><br>
                  <textarea class="form-control" name="content" rows="8" cols="80"><?= $result[0]['content'] ?></textarea>
                </div>
                <div class="form-group">
                  <label>Image</label><br>
                  <img src="images/<?= $result[0]['image'] ?>" width="150" height="150"><br>
                  <input type="file" name="image" value="">
                </div>
                <div class="form-group">
                  <input type="submit" value="submit" name="" class="btn btn-success">
                  <a href="index.php" class="btn btn-warning">Back</a>
                </div>
              </form>
              </div>
            </div>
            <!-- /.card -->

            
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php

 include 'footer.php';
 ?>
