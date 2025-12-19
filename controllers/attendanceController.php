<?php
require_once('../models/attendanceModel.php');
require_once('../models/systemLogModel.php');

class AttendanceController {
    private $attendanceModel;
    private $systemLogModel;

    public function __construct($con) {
        $this->attendanceModel = new AttendanceModel($con);
        $this->systemLogModel = new SystemLogModel($con);
    }
 public function clockIn($employeeId, $deviceId = null) {
        $result = $this->attendanceModel->clockIn($employeeId, $deviceId);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($employeeId, 'Clock In', 'Employee clocked in', $ipAddress);
        }
        return $result;
    }

    public function clockOut($employeeId) {
        $result = $this->attendanceModel->clockOut($employeeId);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($employeeId, 'Clock Out', 'Employee clocked out', $ipAddress);
        }
        return $result;
    }
    public function getAttendance($role, $userId, $departmentId = null) {
        if ($role == 2) {
            return $this->attendanceModel->getAttendanceByEmployee($userId);
        } else {
            return $this->attendanceModel->getAllAttendance($role, $departmentId);
        }
    }
}
?>
