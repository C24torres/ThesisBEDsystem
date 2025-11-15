<?php
require '../../includes/session.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | ONgrade BED</title>

  <?php include '../../includes/links.php' ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <?php include '../../includes/navbar.php' ?>

    <?php include '../../includes/sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $total_stud = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE remark = 'Approved' AND ay_id = '$_SESSION[active_acadyears]' AND semester_id = '$_SESSION[active_semester]'");
                $total = mysqli_num_rows($total_stud);
                ?>
                <h3>
                  <?php echo $total; ?>
                </h3>

                <p>Enrolled Students</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
                <a href="<?php echo $_SESSION['role']== "Registrar" ? "../student/list.students.php" : "#"?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <?php
                        $pending_stud = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE remark = 'Pending' AND ay_id = '$_SESSION[active_acadyears]' AND semester_id = '$_SESSION[active_semester]'");
                        $total = mysqli_num_rows($pending_stud);
                        ?>
                        <h3>
                          <?php echo $total; ?>
                        </h3>

                        <p>Pending Students</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <?php
                        $new_stud = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE remark = 'Approved' AND status = 'New' AND ay_id = '$_SESSION[active_acadyears]' AND semester_id = '$_SESSION[active_semester]'");
                        $total = mysqli_num_rows($new_stud);
                        ?>
                        <h3>
                          <?php echo $total; ?>
                        </h3>

                        <p>New Students</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                        <?php
                        $old_stud = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE remark = 'Approved' AND status = 'Old' AND ay_id = '$_SESSION[active_acadyears]' AND semester_id = '$_SESSION[active_semester]'");
                        $total = mysqli_num_rows($old_stud);
                        ?>
                        <h3>
                          <?php echo $total; ?>
                        </h3>

                        <p>Old Students</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                      </div>
                      <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                  <!-- ./col -->
                </div>
              </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

      <?php include '../../includes/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
  <!-- ./wrapper -->

  <?php include '../../includes/script.php' ?>

</body>

</html>