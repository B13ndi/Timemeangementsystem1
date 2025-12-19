<?php
require('includes/top.inc.php');
require_once('../controllers/employeeController.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: profile.php');
    die();
}

$employeeController = new EmployeeController($con);
$employeeController->handleRequest();

require('includes/footer.inc.php');
?>
