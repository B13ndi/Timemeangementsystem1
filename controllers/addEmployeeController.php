<?php
require('../models/addEmployeeModel.php');

class AddEmployeeController {
    private $model;

    public function __construct($con) {
        $this->model = new AddEmployeeModel($con);
    }
    public function processForm() {
        if (isset($_POST['submit'])) {
            $employeeId = isset($_GET['employeeid']) ? $_GET['employeeid'] : 0;
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $departmentId = $_POST['departmentId'];

            if ($employeeId > 0) {
                $result = $this->model->updateEmployee($employeeId, $name, $email, $password, $departmentId);
                if (isset($result['success']) && !$result['success']) {
                    $_SESSION['error_message'] = $result['message'];
                    header('location: addEmployeeView.php?employeeid=' . $employeeId);
                    die();
                }
            } else {
                $result = $this->model->addEmployee($name, $email, $password, $departmentId);
                if (!$result['success']) {
                    $_SESSION['error_message'] = $result['message'];
                    header('location: addEmployeeView.php');
                    die();
                }
                $_SESSION['success_message'] = $result['message'];
            }
                   header('location: employee.php');
            die();
        }
    }

    public function getEmployeeData($employeeId) {
        return $this->model->getEmployeeById($employeeId);
    }
}
?>