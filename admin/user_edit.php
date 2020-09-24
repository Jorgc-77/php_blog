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
  $name = $_POST['name'];
  $email = $_POST['email'];
  if(empty($_POST['role'])) {
    $role = 0;
  } else {
    $role = 1;
  }
  
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
  $stmt->execute(
          array(':email'=>$email,':id'=>$id)
  );
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
   
  if ($user) {
      echo "<script>alert('Email duplicated')</script>";
    } else {
      $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
      $result = $stmt->execute(
        array(':name'=>$name,':email'=>$email,':role'=>$role)
      );
      if ($result) {
        echo "<script>alert('Successfully Updated');window.location.href='user_list.php';</script>";
      }
    }
}

// edit 
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
$stmt->execute();

$result = $stmt->fetchAll();
// print_r($results);

?>


    <!-- /.email-header -->
    <!-- Main email -->
    <div class="email">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="hidden" name="id" value="<?= $result[0]['id'] ?>">
                  <label>Name</label>
                  <input type="text" class="form-control" name="name" value="<?= $result[0]['name'] ?>" required>
                </div>
                <div class="form-group">
                  <label>Title</label>
                  <input type="email" class="form-control" name="email" value="<?= $result[0]['email'] ?>" required>
                </div>
                <div class="form-group">
                  <label for="vehicle3">Admin</label>
                  <input type="checkbox" name="role" value="yes"
                  <?php
                  if ($result[0]['role'] == 1) { 
                   echo "checked"; 
                 }
                 ?>
                  >
                </div>
                <div class="form-group">
                  <input type="submit" value="submit" name="" class="btn btn-success">
                  <a href="user_list.php" class="btn btn-warning">Back</a>
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
    <!-- /.email -->
  </div>
  <!-- /.email-wrapper -->

 <?php

 include 'footer.php';
 ?>
