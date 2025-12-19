<?php
class EmployeeModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getEmployees() {
        $res = mysqli_query($this->con, "SELECT employee.*, department.departmentName 
                                      FROM employee 
                                      JOIN department ON employee.departmentId = department.departmentId
                                      AND employee.role=2");
        $employees = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $employees[] = $row;
        }
        return $employees;
    }

    public function deleteEmployee($employeeId) {
        mysqli_query($this->con, "DELETE FROM employee WHERE employeeid='$employeeId' AND role=2");
    }
}
?>