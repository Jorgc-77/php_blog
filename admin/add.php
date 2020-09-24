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
  $file = 'images/'.($_FILES['image']['name']);
  $imgType = pathinfo($file,PATHINFO_EXTENSION);

  if ($imgType != 'png' && $imgType != 'jpg' && $imgType != 'jpeg') {
    echo "<script>alert('Image must be png,jpg,jpeg')</script>";
  } else {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],$file);

    $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
    $result = $stmt->execute(
      array(':title'=>$title,':content'=>$content,':author_id'=>$_SESSION['user_id'],':image'=>$image)
    );
    if ($result) {
      echo "<script>alert('Successfully Added');window.location.href='index.php';</script>";
    }

  } 
}

?>


    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="add.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control" name="title" value="" required>
                </div>
                <div class="form-group">
                  <label>Content</label><br>
                  <textarea class="form-control" name="content" rows="8" cols="80"></textarea>
                </div>
                <div class="form-group">
                  <label>Image</label><br>
                  <input type="file" name="image" value="" required="">
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
