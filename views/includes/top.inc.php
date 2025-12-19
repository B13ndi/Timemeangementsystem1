<?php
require(__DIR__ . '/../../public/db.inc.php');
if(!isset($_SESSION['role'])){
    header('location:index.php');
        die();
}
?>

<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="../assets/css/normalize.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/pe-icon-7-filled.css">
    <link rel="stylesheet" href="../assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
   <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                  <?php if($_SESSION['role']==1 ){ ?>
                  <li class="menu-item-has-children dropdown">
                     <a href="index.php" > Department</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="manager.php" > Managers </a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="employee.php" > Employees</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="biometricDeviceView.php" > Biometric Devices</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="systemLogView.php" > System Logs</a>
                  </li>
                  <?php }  elseif($_SESSION['role']==3 ){?>
                    <li class="menu-item-has-children dropdown">
                        <a href="insightsReportView.php">Generate Insights Report</a>
                    </li>
                    <li class="menu-item-has-children dropdown">
                        <a href="profile.php">Profile</a>
                    </li>
                    <?php } 
              else { ?>
                  <li class="menu-item-has-children dropdown">
                   <a href="profile.php?employeeId=<?php echo isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : ''; ?>" > Profile</a>
                     </li>
            <?php } ?>
                  <li class="menu-item-has-children dropdown">
                     <a href="time_off_request.php" >Time Off Requests</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="attendanceView.php">Attendance Tracking</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="notificationsView.php">Notifications</a>
                  </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a id="logotext" class="navbar-brand" href="index.php">Time Attendance Managment</a>
                  <a class="navbar-brand hidden" href="index.php"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                <?php
                // Load notification count
                if (isset($_SESSION['USER_ID'])) {
                    require_once('../models/notificationModel.php');
                    $notificationModel = new NotificationModel($con);
                    $unreadCount = $notificationModel->getUnreadCount($_SESSION['USER_ID']);
                } else {
                    $unreadCount = 0;
                }
                ?>
                <div class="user-area dropdown float-right">
                    <a class="nav-link" href="notificationsView.php" style="margin-right: 15px;">
                      <i class="fa fa-bell"></i> Notifications
                      <?php if ($unreadCount > 0): ?>
                          <span class="badge badge-danger"><?php echo $unreadCount; ?></span>
                      <?php endif; ?>
                    </a>
                    <a class="nav-link" href="../views/logout.php">
                      <i class="fa fa-power-off"></i> Logout
                  </a>
                </div>
               </div>
            </div>
         </header>
