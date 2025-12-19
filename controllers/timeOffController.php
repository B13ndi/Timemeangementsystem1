<?php
require_once('../models/timeOffModel.php');
require_once('../models/notificationModel.php');
require_once('../models/systemLogModel.php');

class TimeOffController {
    private $model;
    private $notificationModel;
    private $systemLogModel;

    public function __construct($con) {
        $this->model = new TimeOffModel($con);
        $this->notificationModel = new NotificationModel($con);
        $this->systemLogModel = new SystemLogModel($con);
    }

    public function processRequests($role, $userId, $departmentId = null) {
        if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id'])) {
            $id = mysqli_real_escape_string($this->model->con, $_GET['id']);
            
            // Merr detajet e kërkesës para fshirjes
            $requestInfo = mysqli_query($this->model->con, "SELECT employee_id FROM time_off_request WHERE id='$id'");
            $request = mysqli_fetch_assoc($requestInfo);
            
            $this->model->deleteTimeOffRequest($id);
            
            // Log veprimin
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($userId, 'Delete Time Off Request', "Deleted request ID: $id", $ipAddress);
            
            header('location: time_off_request.php');
            die();
        }

        if (isset($_GET['type']) && $_GET['type'] == 'update' && isset($_GET['id']) && isset($_GET['status'])) {
            $id = mysqli_real_escape_string($this->model->con, $_GET['id']);
            $status = mysqli_real_escape_string($this->model->con, $_GET['status']);
            
            // Merr detajet e kërkesës dhe informacionin e punonjësit
            $requestInfo = mysqli_query($this->model->con, "SELECT tor.employee_id, tor.startdate, tor.enddate, tor.leave_type, 
                                                           e.name as employee_name, e.role as employee_role, e.departmentId,
                                                           approver.name as approver_name
                                                           FROM time_off_request tor
                                                           JOIN employee e ON tor.employee_id = e.employeeid
                                                           LEFT JOIN employee approver ON approver.employeeid = '$userId'
                                                           WHERE tor.id='$id'");
            $request = mysqli_fetch_assoc($requestInfo);
            
            if ($request) {
                // Update status
                $this->model->updateTimeOffRequestStatus($id, $status);
                
                // Update approved_by
                if ($status == 2 || $status == 3) {
                    mysqli_query($this->model->con, "UPDATE time_off_request SET approved_by='$userId' WHERE id='$id'");
                }
                
                $statusText = ($status == 2) ? 'Miratuar' : 'Refuzuar';
                $statusTextEn = ($status == 2) ? 'Approved' : 'Rejected';
                $approverName = $request['approver_name'] ?? 'Sistemi';
                $employeeName = $request['employee_name'];
                $leaveType = $request['leave_type'];
                $startDate = $request['startdate'];
                $endDate = $request['enddate'];
                $employeeDepartmentId = $request['departmentId'];
                
                // Llogarit ditët
                $daysRequested = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
                
                // Njoftim për punonjësin që bëri kërkesën
                $subject = "Kërkesa juaj për pushim u $statusText";
                $body = "Kërkesa juaj për pushim u $statusText nga $approverName.\n";
                $body .= "Lloji: $leaveType\n";
                $body .= "Data e fillimit: $startDate\n";
                $body .= "Data e mbarimit: $endDate\n";
                $body .= "Ditët: $daysRequested";
                $this->notificationModel->addNotification($request['employee_id'], $subject, $body);
                
                // Njoftim për adminët
                $adminSubject = "Kërkesë për pushim u $statusText";
                $adminBody = "Kërkesa për pushim e $employeeName u $statusText nga $approverName.\n";
                $adminBody .= "Lloji: $leaveType\n";
                $adminBody .= "Data e fillimit: $startDate\n";
                $adminBody .= "Data e mbarimit: $endDate\n";
                $adminBody .= "Ditët: $daysRequested";
                $this->notificationModel->notifyAllAdmins($adminSubject, $adminBody);
                
                // Nëse punonjësi është punonjës (role=2), dërgo njoftim menaxherëve të departamentit të tij
                if ($request['employee_role'] == 2 && $employeeDepartmentId) {
                    $this->notificationModel->notifyManagersByDepartment($employeeDepartmentId, $adminSubject, $adminBody);
                }
                
                // Log veprimin
                $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
                $this->systemLogModel->addLog($userId, 'Update Time Off Request', "Updated request ID: $id to status: $status", $ipAddress);
            }
            
            header('location: time_off_request.php');
            die();
        }

        $requests = $this->model->getTimeOffRequests($role, $userId, $departmentId);

        return $requests;
    }
}
?>
