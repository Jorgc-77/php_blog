<?php

session_start();
require '../config/config.php';
include 'header.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_POST) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    if(empty($_POST['role'])) {
      $role = 0;
    } else {
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email duplicated')</script>";
    } else {
      $stmt = $pdo->prepare("INSERT INTO users(name,email,role,password) VALUES (:name,:email,:role,:password)");
      $result = $stmt->execute(
        array(':name'=>$name,':email'=>$email,':role'=>$role,':password'=>$password)
      );
      if ($result) {
        echo "<script>alert('Successfully Added');window.location.href='user_list.php';</script>";
      }
    }

  } 


?>


    <!-- /.name-header -->

    <!-- Main name -->
    <div class="name">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="user_add.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control" name="name" value="" required>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" value="" required>
                </div>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="vehicle3">Admin</label>
                  <input type="checkbox" name="role" value="1">
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
    <!-- /.name -->
  </div>
  <!-- /.name-wrapper -->

 <?php

 include 'footer.php';
 ?>
