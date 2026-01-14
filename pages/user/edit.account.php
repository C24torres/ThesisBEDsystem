<?php
require '../../includes/session.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Account | OnGrade - Bacoor</title>

    <?php include '../../includes/links.php'; ?>

</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
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
                            <h1 class="m-0">Edit Account</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"></a></li>
                                <li class="breadcrumb-item active"></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <?php
                                
                                if ($_SESSION['role'] == "Student") {
                                    $user_info = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id = '$_SESSION[id]'");
                                    $row = mysqli_fetch_array($user_info);

                                } elseif ($_SESSION['role'] == "Super Administrator") {
                                    $user_info = mysqli_query($conn, "SELECT * FROM tbl_master_key WHERE mk_id = '$_SESSION[id]'");
                                    $row = mysqli_fetch_array($user_info);

                                } elseif ($_SESSION['role'] == "Adviser") {
                                    $user_info = mysqli_query($conn, "SELECT * FROM tbl_advisers WHERE ad_id = '$_SESSION[id]'");
                                    $row = mysqli_fetch_array($user_info);
                                    
                                } elseif ($_SESSION['role'] == "Faculty Staff") {
                                    $user_info = mysqli_query($conn, "SELECT * FROM tbl_teachers WHERE teacher_id = '$_SESSION[id]'");
                                    $row = mysqli_fetch_array($user_info);
                                    
                                }  elseif ($_SESSION['role'] == "Registrar") {
                                    $user_info = mysqli_query($conn, "SELECT * FROM tbl_registrars WHERE reg_id = '$_SESSION[id]'");
                                    $row = mysqli_fetch_array($user_info);
                                    
                                }
                                
                                ?>
                                <form method="POST" action="userData/ctrl.edit.account.php">
                                    <div class="card-header">
                                        <h3 class="card-title">User Account Information</h3>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input class="form-control" type="text" name="username" value="<?php echo $row['username']?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control" type="email" name="email" value="<?php echo $row['email']?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>New Password</label>
                                                    <input class="form-control" type="password" name="password" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Confirm Password</label>
                                                    <input class="form-control" type="password" name="confirm_pass" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-sm float-right"
                                            name="submit">Update Account</button>
                                    </div>
                                </form>
                                <!-- /.card-footer-->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
</body>

</html>