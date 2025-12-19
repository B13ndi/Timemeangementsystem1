
<?php
require(__DIR__ . '/includes/top.inc.php');
require(__DIR__ . '/../models/departmentModel.php');
require(__DIR__ . '/departmentView.php');
require(__DIR__ . '/../controllers/departmentController.php');

$departmentModel = new DepartmentModel($con);
$departmentView = new DepartmentView();
$departmentController = new DepartmentController($con, $departmentModel, $departmentView);

$departmentController->handleRequest();

require('includes/footer.inc.php');
