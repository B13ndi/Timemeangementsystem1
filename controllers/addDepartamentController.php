  <?php
require('../models/AddDepartmentModel.php');
class AddDepartmentController {
    private $addDepartmentModel;

    public function __construct($con) {
        $this->addDepartmentModel = new AddDepartmentModel($con);
    }

    public function addDepartment($departmentName) {
        $this->addDepartmentModel->addDepartment($departmentName);
        header('location: index.php');  
        die();
    }
}
?>