<?php
class TimeOffModel {
    public $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getTimeOffRequests($role, $userId, $departmentId = null) {
        $userId = mysqli_real_escape_string($this->con, $userId);
        $departmentId = $departmentId ? mysqli_real_escape_string($this->con, $departmentId) : null;
        
        if ($role == 1) {
            // Admin - shfaq të gjitha kërkesat
            $sql = "SELECT tor.*, employee.name, employee.email, d.departmentName
                    FROM `time_off_request` tor
                    JOIN employee ON tor.employee_id = employee.employeeid
                    LEFT JOIN department d ON employee.departmentId = d.departmentId
                    ORDER BY tor.id DESC";
        } elseif ($role == 3 && $departmentId) {
            // Manager - vetëm kërkesat e departamentit të tij
            $sql = "SELECT tor.*, employee.name, employee.email, d.departmentName
                    FROM `time_off_request` tor
                    JOIN employee ON tor.employee_id = employee.employeeid
                    LEFT JOIN department d ON employee.departmentId = d.departmentId
                    WHERE employee.departmentId = '$departmentId'
                    ORDER BY tor.id DESC";
        } else {
            // Employee - vetëm kërkesat e veta
            $sql = "SELECT tor.*, employee.name, employee.email, d.departmentName
                    FROM `time_off_request` tor
                    JOIN employee ON tor.employee_id = employee.employeeid
                    LEFT JOIN department d ON employee.departmentId = d.departmentId
                    WHERE tor.employee_id = '$userId'
                    ORDER BY tor.id DESC";
        }
        
        $res = mysqli_query($this->con, $sql);
        
        return $res;
    }

    public function deleteTimeOffRequest($id) {
        mysqli_query($this->con, "DELETE FROM `time_off_request` WHERE id='$id'");
    }

    public function updateTimeOffRequestStatus($id, $status) {
        mysqli_query($this->con, "UPDATE `time_off_request` SET status='$status' WHERE id='$id'");
    }
}
?>
