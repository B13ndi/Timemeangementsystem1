<?php
require('includes/top.inc.php');
require_once('../controllers/managerController.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: profile.php');
    die();
}

$managerController = new ManagerController($con);
$managerController->handleRequest();

require('includes/footer.inc.php');
?>
