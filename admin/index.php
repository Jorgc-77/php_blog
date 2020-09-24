<?php

session_start();
require '../config/config.php';
include 'header.php';

if (!$_SESSION['user_id'] && !$_SESSION['logged_in']) {
  header('Location: login.php');
}
if ($_SESSION['role'] != 1) {
  header('Location: login.php');
}

if (!empty($_POST['search'])) {
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
  if(empty($_GET['pageno'])) {
    unset($_COOKIE['search']);
    setcookie('search', null, -1, "/");
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
              <div class="card-header">
                <h3 class="card-title">Blog Lists</h3>
              </div>

              <?php
                if (!empty($_GET['pageno'])){
                  $pageno = $_GET['pageno'];
                }else {
                  $pageno = 1;
                }
                $numOfrecs = 4;
                $offset = ($pageno - 1) * $numOfrecs; 

                 if(empty($_POST['search']) && empty($_COOKIE['search'])) {
                  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                  $stmt->execute();
                  $rawResults = $stmt->fetchAll();
                  $total_pages = ceil(count($rawResults) / $numOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                  $stmt->execute();
                  $results = $stmt->fetchAll();
                } else {
                 $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                  $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ");
                  // print_r($stmt);exit(); 
                  $stmt->execute();
                  $rawResults = $stmt->fetchAll();
                  $total_pages = ceil(count($rawResults) / $numOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                  $stmt->execute();
                  $results = $stmt->fetchAll();
                }
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div >
                  <a href="add.php" type="button" class="btn btn-success">New Blog Post</a>
                </div><br>

                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th>Images</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      if ($results) {
                        $i = 1;
                        foreach ($results as $result) { ?>
                      <tr>
                      <td><?= $i++; ?></td>
                      <td><?=  $result['title'] ?></td>
                      <td>
                        <?= substr($result['content'], 0,50) ?>
                      </td>
                      <td>
                        <img src="images/<?= $result['image'] ?>" width="150" height="130" >
                      </td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="edit.php?id=<?= $result['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="delete.php?id=<?= $result['id'] ?>"
                               onclick="return confirm('Are you sure you want to delete this item?');"
                             type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                         <?php  
                        }
                      }
                    ?>
                    
                  </tbody>
                </table><br>
                <nav aria-label="..." style="float:right;">
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
              </div>
              <!-- /.card-body -->
            
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
