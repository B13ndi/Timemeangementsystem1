<?php
class SystemLogModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function addLog($employeeId, $action, $details, $ipAddress = null) {
        $employeeId = $employeeId ? mysqli_real_escape_string($this->con, $employeeId) : 'NULL';
        $action = mysqli_real_escape_string($this->con, $action);
        $details = mysqli_real_escape_string($this->con, $details);
        $ipAddress = $ipAddress ? mysqli_real_escape_string($this->con, $ipAddress) : 'NULL';

        $sql = "INSERT INTO systemlog(employeeId, action, details, ip_address) VALUES (" . 
               ($employeeId != 'NULL' ? "'$employeeId'" : 'NULL') . ", '$action', '$details', " . 
               ($ipAddress != 'NULL' ? "'$ipAddress'" : 'NULL') . ")";
        return mysqli_query($this->con, $sql);
    }

    public function getLogs($limit = 100) {
        $limit = (int)$limit;
        $res = mysqli_query($this->con, "SELECT sl.*, e.name as employee_name 
                                         FROM systemlog sl 
                                         LEFT JOIN employee e ON sl.employeeId = e.employeeid 
                                         ORDER BY sl.action_time DESC 
                                         LIMIT $limit");
        $logs = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $logs[] = $row;
        }
        return $logs;
    }

    public function getLogsByEmployee($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT * FROM systemlog WHERE employeeId='$employeeId' ORDER BY action_time DESC");
        $logs = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $logs[] = $row;
        }
        return $logs;
    }

    public function getLogsByAction($action) {
        $action = mysqli_real_escape_string($this->con, $action);
        $res = mysqli_query($this->con, "SELECT sl.*, e.name as employee_name 
                                         FROM systemlog sl 
                                         LEFT JOIN employee e ON sl.employeeId = e.employeeid 
                                         WHERE sl.action='$action' 
                                         ORDER BY sl.action_time DESC");
        $logs = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $logs[] = $row;
        }
        return $logs;
    }
}
?>

