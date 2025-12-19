<?php
class NotificationModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getNotifications($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT * FROM notification WHERE sent_to='$employeeId' ORDER BY created_at DESC");
        $notifications = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $notifications[] = $row;
        }
        return $notifications;
    }

    public function getUnreadCount($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT COUNT(*) as count FROM notification WHERE sent_to='$employeeId' AND is_read=0");
        $row = mysqli_fetch_assoc($res);
        return $row['count'];
    }

    public function addNotification($sentTo, $subject, $body) {
        $sentTo = mysqli_real_escape_string($this->con, $sentTo);
        $subject = mysqli_real_escape_string($this->con, $subject);
        $body = mysqli_real_escape_string($this->con, $body);

        $sql = "INSERT INTO notification(sent_to, subject, body) VALUES ('$sentTo', '$subject', '$body')";
        return mysqli_query($this->con, $sql);
    }

    public function markAsRead($notificationId) {
        $notificationId = mysqli_real_escape_string($this->con, $notificationId);
        return mysqli_query($this->con, "UPDATE notification SET is_read=1 WHERE notificationId='$notificationId'");
    }

    public function markAllAsRead($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        return mysqli_query($this->con, "UPDATE notification SET is_read=1 WHERE sent_to='$employeeId'");
    }

    public function deleteNotification($notificationId) {
        $notificationId = mysqli_real_escape_string($this->con, $notificationId);
        return mysqli_query($this->con, "DELETE FROM notification WHERE notificationId='$notificationId'");
    }

    // Merr të gjithë adminët (role = 1)
    public function getAllAdmins() {
        $res = mysqli_query($this->con, "SELECT employeeid FROM employee WHERE role=1");
        $admins = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $admins[] = $row['employeeid'];
        }
        return $admins;
    }

    // Merr të gjithë menaxherët (role = 3)
    public function getAllManagers() {
        $res = mysqli_query($this->con, "SELECT employeeid FROM employee WHERE role=3");
        $managers = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $managers[] = $row['employeeid'];
        }
        return $managers;
    }

    // Merr menaxherët e një departamenti të caktuar
    public function getManagersByDepartment($departmentId) {
        $departmentId = mysqli_real_escape_string($this->con, $departmentId);
        $res = mysqli_query($this->con, "SELECT employeeid FROM employee WHERE role=3 AND departmentId='$departmentId'");
        $managers = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $managers[] = $row['employeeid'];
        }
        return $managers;
    }

    // Merr rolin e një punonjësi
    public function getEmployeeRole($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT role FROM employee WHERE employeeid='$employeeId'");
        $row = mysqli_fetch_assoc($res);
        return $row ? $row['role'] : null;
    }

    // Merr departamentin e një punonjësi
    public function getEmployeeDepartment($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT departmentId FROM employee WHERE employeeid='$employeeId'");
        $row = mysqli_fetch_assoc($res);
        return $row ? $row['departmentId'] : null;
    }

    // Dërgo njoftim për të gjithë adminët
    public function notifyAllAdmins($subject, $body) {
        $admins = $this->getAllAdmins();
        foreach ($admins as $adminId) {
            $this->addNotification($adminId, $subject, $body);
        }
    }

    // Dërgo njoftim për të gjithë menaxherët e një departamenti
    public function notifyManagersByDepartment($departmentId, $subject, $body) {
        $managers = $this->getManagersByDepartment($departmentId);
        foreach ($managers as $managerId) {
            $this->addNotification($managerId, $subject, $body);
        }
    }
}
?>

