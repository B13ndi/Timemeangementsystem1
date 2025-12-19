<?php
class ManagerModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getManagers() {
        $res = mysqli_query($this->con, "SELECT employee.*, department.departmentName 
                                      FROM employee 
                                      JOIN department ON employee.departmentId = department.departmentId 
                                      AND employee.role=3");
        $managers = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $managers[] = $row;
        }
        return $managers;
    }

    public function deleteManager($employeeId) {
        mysqli_query($this->con, "DELETE FROM employee WHERE employeeid='$employeeId' AND role=3");
    }
}
?>