<?php
class CreateTimeOffModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getCon() {
        return $this->con;
    }

    public function addTimeOffRequest($leaveType, $startDate, $endDate, $employeeId, $reason, $medicalDocument) {
        return $this->insertTimeOffRequest($leaveType, $startDate, $endDate, $employeeId, $reason, $medicalDocument);
    }

    private function insertTimeOffRequest($leaveType, $startDate, $endDate, $employeeId, $reason, $medicalDocument) {
        $sql = "INSERT INTO `time_off_request` (leave_type, startdate, enddate, employee_id, reason, medical_document, status)
                VALUES (?, ?, ?, ?, ?, ?, 1)";

        $stmt = mysqli_prepare($this->con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssssss', $leaveType, $startDate, $endDate, $employeeId, $reason, $medicalDocument);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        }
        
        return false;
    }
}
?>
