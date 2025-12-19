<?php
require_once('../models/createTimeOffModel.php');
require_once('../models/notificationModel.php');

class CreateTimeOffController {
    private $model;
    private $notificationModel;

    public function __construct($con) {
        $this->model = new CreateTimeOffModel($con);
        $this->notificationModel = new NotificationModel($con);
    }

    public function processTimeOffRequestForm() {
        if (isset($_POST['submit'])) {
            
            if (empty($_POST['leave_type']) || empty($_POST['startdate']) || empty($_POST['enddate'])) {
                header('location: create_request.php?msg=Please fill all required fields');
                die();
            }

            $leaveType = mysqli_real_escape_string($this->model->getCon(), $_POST['leave_type']);
            $startDate = mysqli_real_escape_string($this->model->getCon(), $_POST['startdate']);
            $endDate = mysqli_real_escape_string($this->model->getCon(), $_POST['enddate']);
            
            
            if ($endDate < $startDate) {
                header('location: create_request.php?msg=End date must be after or equal to start date');
                die();
            }
             $employeeId = $_SESSION['USER_ID'];
            $reason = isset($_POST['reason']) ? mysqli_real_escape_string($this->model->getCon(), $_POST['reason']) : '';

            $medicalDocument = '';
            if (isset($_FILES['medical_document']) && $_FILES['medical_document']['error'] == UPLOAD_ERR_OK) {
                
                $fileType = $_FILES['medical_document']['type'];
                if ($fileType != 'application/pdf') {
                    header('location: create_request.php?msg=Only PDF files are allowed');
                    die();
                }
                
                
                $uploadDir = __DIR__ . '/../uploads/medical_documents/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                
                $fileExtension = pathinfo($_FILES['medical_document']['name'], PATHINFO_EXTENSION);
                $fileName = 'medical_' . $employeeId . '_' . time() . '.' . $fileExtension;
                $filePath = $uploadDir . $fileName;
                
                
                if (move_uploaded_file($_FILES['medical_document']['tmp_name'], $filePath)) {
                    $medicalDocument = 'uploads/medical_documents/' . $fileName;
                }
            }

            $result = $this->model->addTimeOffRequest($leaveType, $startDate, $endDate, $employeeId, $reason, $medicalDocument);

            if ($result) {
                
                $employeeInfo = mysqli_query($this->model->getCon(), "SELECT name, role, departmentId FROM employee WHERE employeeid='$employeeId'");
                $employee = mysqli_fetch_assoc($employeeInfo);
                
                if ($employee) {
                    $employeeName = $employee['name'];
                    $employeeRole = $employee['role'];
                    $employeeDepartmentId = $employee['departmentId'];
                    
                    
                    $daysRequested = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) + 1;
                    
                    
                    $subject = "Kërkesë e re për pushim";
                    $body = "$employeeName ka bërë një kërkesë për pushim.\n";
                    $body .= "Lloji: $leaveType\n";
                    $body .= "Data e fillimit: $startDate\n";
                    $body .= "Data e mbarimit: $endDate\n";
                    $body .= "Ditët e kërkuara: $daysRequested\n";
                    if (!empty($reason)) {
                        $body .= "Arsyeja: $reason";
                    }
                    
                    
                    $this->notificationModel->notifyAllAdmins($subject, $body);
                    
                    
                    if ($employeeRole == 2 && $employeeDepartmentId) {
                        $this->notificationModel->notifyManagersByDepartment($employeeDepartmentId, $subject, $body);
                    }
                    
                }
                
                header('location: time_off_request.php?msg=Request created successfully');
            } else {
                header('location: create_request.php?msg=Error creating request. Please try again.');
            }
            die();
        }
    }
}
?>
