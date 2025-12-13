
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://stfrancis.edu.ph/bedprogram/" alt="logo.png" class="brand-link">
      <img src="../../docs/assets/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">OnGrade BED</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        <?php
                        if (!empty($row['img'])) {
                        ?>
                        <img style="width: 40px; height: 40px;" src="data:image/jpeg;base64,<?php echo base64_encode($row['img']) ?>" class="img-circle elevation-2 mt-2" alt="User Image">
                        <?php
                        } else {
                        ?>
                        <img style="width: 40px; height: 40px;" src="../../docs/assets/img/user.png" class="img-circle elevation-2 mt-2" alt="User Image">
                        <?php
                        }
                        ?>
        </div>
        <div class="info">
          <a class="d-block"><?php echo $_SESSION['name']; ?></a>
          <p class="mb-0"><small><?php echo $_SESSION['role']; ?></small></p>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="../dashboard/index.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <?php
            if ($_SESSION['role'] == "Super Administrator") { /////////////////////// Super Administrator sidebar
          ?>
          <li class="nav-item">
            <a href="../faculty/faculty.load.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Faculty's Load
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../student/list.students.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Enrolled Students List
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Students' Forms
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../grade/student.record.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Permanent Record</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="../grade/student.summary.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Summary of Grade</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            } elseif ($_SESSION['role'] == "Registrar")  {/////////////////////// Registrar sidebar
          ?>
          <li class="nav-item">
            <a href="../faculty/faculty.load.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Faculty's Load
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Students' Ranking
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../grade/student.record.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Senior High School</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../enrolled/grade.eleven.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 11</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.twelve.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 12</p>
                  </a>
                </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Junior High School</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../enrolled/grade.seven.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 7</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.eight.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 8</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.nine.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 9</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.ten.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 10</p>
                  </a>
                </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Grade School</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../enrolled/grade.one.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 1</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.two.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 2</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.three.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 3</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.four.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 4</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.five.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 5</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/grade.six.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Grade 6</p>
                  </a>
                </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Pre School</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="../enrolled/nursery.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Nursery</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/pre.kinder.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Pre-Kinder</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../enrolled/kinder.php" class="nav-link">
                    <i class="far fa-file nav-icon"></i>
                    <p>Kinder</p>
                  </a>
                </li>
                </ul>
              </li>
            </ul>
          </li>


          <li class="nav-item">
            <a href="../student/list.students.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Enrolled SHS Students List
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../student/list.elem.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Enrolled Elementary Students List
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Students' Forms
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../grade/student.record.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Permanent Record</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="../grade/student.summary.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Summary of Grade</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            } elseif ($_SESSION['role'] == "Faculty Staff") { /////////////////////// Faculty Staff sidebar
          ?>
          <li class="nav-item">
            <a href="../faculty/view.load.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Subjects Loads
              </p>
            </a>
          </li>
          <?php
            } elseif ($_SESSION['role'] == "Student") { /////////////////////// Student sidebar
          ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Students' Forms
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../grade/student.record.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Permanent Record</p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="../grade/summary.grade.php" class="nav-link">
                  <i class="far fa-file nav-icon"></i>
                  <p>Student's Summary of Grade</p>
                </a>
              </li>
            </ul>
          </li>
          <?php
            } 
          ?>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>