<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>
<?php
session_start();
if (isset($_SESSION['role'])) {
    header("location: pages/dashboard/index.php");
} else {
    header("location: pages/login/login.php");
}

?>
</body>
</html>
