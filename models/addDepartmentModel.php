<?php
class AddDepartmentModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function addDepartment($departmentName) {
        $departmentName = mysqli_real_escape_string($this->con, $departmentName);

        $sql = "INSERT INTO department(departmentName) VALUES ('$departmentName')";
        mysqli_query($this->con, $sql);
    }
}