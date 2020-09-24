<?php

session_start();
require 'config/config.php';


if (!$_SESSION['user_id'] && !$_SESSION['logged_in']) {
  header('Location: login.php');
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini" style="overflow-x: hidden;">
<div class="" >
 
  

  <!-- Content Wrapper. Contains page content -->
  <div class="">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1 class="text-center">Blog Sites</h1>
      </div><!-- /.container-fluid -->
    </section>
    <?php
    if (!empty($_GET['pageno'])){
      $pageno = $_GET['pageno'];
    }else {
      $pageno = 1;
    }
    $numOfrecs = 6;
    $offset = ($pageno - 1) * $numOfrecs; 

    if(empty($_POST['search'])) {
      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
      $stmt->execute();
      $rawResults = $stmt->fetchAll();
      $total_pages = ceil(count($rawResults) / $numOfrecs);

      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs ");
      $stmt->execute();
      $results = $stmt->fetchAll();
    }
    ?>
    <div class="row">
      <?php 
      if ($results) {
        $i = 1;
        foreach ($results as $result) { ?>
          <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div style="text-align:center !important;float: none;" class="card-title">
                  <h4><?= $result['title'] ?></h4>
                </div>
                <!-- /.user-block -->

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="blogdetail.php?id=<?= $result['id']; ?>">
                  <img class="img-fluid pad"  src="admin/images/<?= $result['image']?>" style="width: 100%;height: 300px;">
                </a>
              </div>
              <!-- /.card-body -->


            </div>
            <!-- /.card -->
          </div>
           <?php  
         }
       }
       ?> 
      
    </div>

    <div class="row" style="float: right;margin-right: 0px;">
      <nav aria-label="...">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="?pageno=1" tabindex="-1">First</a>
          </li>
          <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
            <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1);} ?>">Previous</a>
          </li>
          <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
            <a class="page-link" href="#"><?php echo $pageno; ?> <span class="sr-only">(current)</span></a>
          </li>
          <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>"><a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1);} ?>">Next</a></li>
          <li class="page-item">
            <a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a>
          </li>
        </ul>
      </nav>
    </div><br><br>


    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px !important;">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.5
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
