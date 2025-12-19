<?php
require('../models/logoutModel.php');
require('../controllers/logoutController.php');

$logoutModel = new LogoutModel();
$logoutController = new LogoutController($logoutModel);
$logoutController->logout();
?>
