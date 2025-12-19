<?php

class DepartmentController {
    private $con;
    private $departmentModel;
    private $departmentView;

    public function __construct($con, $departmentModel, $departmentView) {
        $this->con = $con;
        $this->departmentModel = $departmentModel;
        $this->departmentView = $departmentView;
    }

    public function handleRequest() {

        if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
            
            header('location: ../views/error_page.php');
            die();
        }

       
        if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['departmentId'])) {
             $departmentId = mysqli_real_escape_string($this->con, $_GET['departmentId']);
             $this->departmentModel->deleteDepartment($departmentId);
            }
        
      
        $departments = $this->departmentModel->getDepartments();
        $this->departmentView->displayDepartments($departments);
    }
}
?>