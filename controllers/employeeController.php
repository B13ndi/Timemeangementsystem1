<?php
require_once('../models/EmployeeModel.php');
require_once('../views/EmployeeView.php');

class EmployeeController {
    private $employeeModel;
    private $employeeView;
    private $con;

    public function __construct($con) {
        $this->con = $con;
        require_once('../models/EmployeeModel.php');
        require_once('../views/EmployeeView.php');
        $this->employeeModel = new EmployeeModel($con);
        $this->employeeView = new EmployeeView();
    }

    public function handleRequest() {
        if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['employeeid'])) {
            $employeeId = mysqli_real_escape_string($this->con, $_GET['employeeid']);
            $this->employeeModel->deleteEmployee($employeeId);
        }

        $employees = $this->employeeModel->getEmployees();
        $this->employeeView->displayEmployees($employees);
    }
}