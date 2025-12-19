<?php
class EditDepartmentModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function editDepartment($departmentId, $newDepartmentName) {
        $departmentId = mysqli_real_escape_string($this->con, $departmentId);
        $newDepartmentName = mysqli_real_escape_string($this->con, $newDepartmentName);

        $sql = "UPDATE department SET departmentName='$newDepartmentName' WHERE departmentId='$departmentId'";
        mysqli_query($this->con, $sql);
    }
}
?>
