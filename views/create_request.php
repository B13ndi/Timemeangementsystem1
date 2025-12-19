<?php
require('includes/top.inc.php');
require_once('../controllers/createTimeOffController.php');
require_once('../views/createTimeOffView.php');

if (!isset($_SESSION['role']) || !isset($_SESSION['USER_ID'])) {
    header('location: login.php');
    die();
}

// Vetëm punonjësit mund të krijojnë kërkesa
if ($_SESSION['role'] != 2) {
    header('location: time_off_request.php');
    die();
}

$msg = '';
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

$createTimeOffController = new CreateTimeOffController($con);
$createTimeOffView = new CreateTimeOffView();

$createTimeOffController->processTimeOffRequestForm();
$createTimeOffView->renderTimeOffRequestForm($msg);

require('includes/footer.inc.php');
?>
