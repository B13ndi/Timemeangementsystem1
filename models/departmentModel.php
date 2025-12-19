<?php
class DepartmentModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getDepartments() {
        $res = mysqli_query($this->con, "SELECT * FROM department ORDER BY departmentId desc");
        $departments = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $departments[] = $row;
        }
        return $departments;
    }

    public function deleteDepartment($departmentId) {
        mysqli_query($this->con, "DELETE FROM department WHERE departmentId='$departmentId'");
    }
}