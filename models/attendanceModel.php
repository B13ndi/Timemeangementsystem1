<?php
class AttendanceModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function clockIn($employeeId, $deviceId = null) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $date = date('Y-m-d');
        $checkIn = date('Y-m-d H:i:s');
        $deviceId = $deviceId ? mysqli_real_escape_string($this->con, $deviceId) : 'NULL';

        // Kontrollo nëse ka regjistrim për sot
        $check = mysqli_query($this->con, "SELECT * FROM attendance WHERE employeeId='$employeeId' AND date='$date'");
        
        if (mysqli_num_rows($check) > 0) {
            // Update nëse ekziston
            $sql = "UPDATE attendance SET checkIn='$checkIn', deviceId=" . ($deviceId != 'NULL' ? "'$deviceId'" : 'NULL') . " WHERE employeeId='$employeeId' AND date='$date'";
        } else {
            // Insert nëse nuk ekziston
            $sql = "INSERT INTO attendance(employeeId, date, checkIn, deviceId) VALUES ('$employeeId', '$date', '$checkIn', " . ($deviceId != 'NULL' ? "'$deviceId'" : 'NULL') . ")";
        }
        
        return mysqli_query($this->con, $sql);
    }

    public function clockOut($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $date = date('Y-m-d');
        $checkOut = date('Y-m-d H:i:s');

        $sql = "UPDATE attendance SET checkOut='$checkOut' WHERE employeeId='$employeeId' AND date='$date'";
        return mysqli_query($this->con, $sql);
    }

    public function getAttendanceByEmployee($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT a.*, e.name, d.departmentName 
                                         FROM attendance a 
                                         JOIN employee e ON a.employeeId = e.employeeid 
                                         LEFT JOIN department d ON e.departmentId = d.departmentId
                                         WHERE a.employeeId='$employeeId' 
                                         ORDER BY a.date DESC");
        $attendance = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $attendance[] = $row;
        }
        return $attendance;
    }

    public function getAttendanceByDate($date) {
        $date = mysqli_real_escape_string($this->con, $date);
        $res = mysqli_query($this->con, "SELECT a.*, e.name, d.departmentName 
                                         FROM attendance a 
                                         JOIN employee e ON a.employeeId = e.employeeid 
                                         LEFT JOIN department d ON e.departmentId = d.departmentId
                                         WHERE a.date='$date' 
                                         ORDER BY a.checkIn DESC");
        $attendance = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $attendance[] = $row;
        }
        return $attendance;
    }

    public function getTodayAttendance($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $date = date('Y-m-d');
        $res = mysqli_query($this->con, "SELECT * FROM attendance WHERE employeeId='$employeeId' AND date='$date'");
        return mysqli_fetch_assoc($res);
    }

    public function getAllAttendance($role, $departmentId = null) {
        if ($role == 1) {
            // Admin - shfaq të gjithë
            $sql = "SELECT a.*, e.name, d.departmentName 
                    FROM attendance a 
                    JOIN employee e ON a.employeeId = e.employeeid 
                    LEFT JOIN department d ON e.departmentId = d.departmentId
                    ORDER BY a.date DESC, a.checkIn DESC";
        } elseif ($role == 3 && $departmentId) {
            // Manager - vetëm departamenti i tij
            $sql = "SELECT a.*, e.name, d.departmentName 
                    FROM attendance a 
                    JOIN employee e ON a.employeeId = e.employeeid 
                    LEFT JOIN department d ON e.departmentId = d.departmentId
                    WHERE e.departmentId='$departmentId'
                    ORDER BY a.date DESC, a.checkIn DESC";
        } else {
            return [];
        }
        
        $res = mysqli_query($this->con, $sql);
        $attendance = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $attendance[] = $row;
        }
        return $attendance;
    }
}
?>

