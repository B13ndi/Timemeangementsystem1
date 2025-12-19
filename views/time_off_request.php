<?php
require('includes/top.inc.php');
require_once('../controllers/timeOffController.php');
require_once('timeOffView.php');

if (!isset($_SESSION['role']) || !isset($_SESSION['USER_ID'])) {
    header('location: login.php');
    die();
}

$role = $_SESSION['role'];
$userId = $_SESSION['USER_ID'];
$departmentId = isset($_SESSION['USER_DEPARTMENT_ID']) ? $_SESSION['USER_DEPARTMENT_ID'] : null;

$msg = '';
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

$controller = new TimeOffController($con);
$requests = $controller->processRequests($role, $userId, $departmentId);

$view = new TimeOffView();
$view->renderTimeOffRequests($requests, $role, $msg);

require('includes/footer.inc.php');
?>
