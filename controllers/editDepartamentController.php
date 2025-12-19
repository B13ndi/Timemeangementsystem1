<?php
require('../db.inc.php');
require('../models/EditDepartmentModel.php');

class EditDepartmentController {
    private $editDepartmentModel;

    public function __construct($con) {
        $this->editDepartmentModel = new EditDepartmentModel($con);
    }

    public function editDepartment($departmentId, $newDepartmentName) {
        $this->editDepartmentModel->editDepartment($departmentId, $newDepartmentName);
        header('location: index.php');  
        die();
    }
}

?>