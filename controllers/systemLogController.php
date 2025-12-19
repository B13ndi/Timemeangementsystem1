<?php
require_once('../models/systemLogModel.php');

class SystemLogController {
    private $systemLogModel;

    public function __construct($con) {
        $this->systemLogModel = new SystemLogModel($con);
    }

    public function getLogs($limit = 100) {
        return $this->systemLogModel->getLogs($limit);
    }

    public function getLogsByEmployee($employeeId) {
        return $this->systemLogModel->getLogsByEmployee($employeeId);
    }

    public function getLogsByAction($action) {
        return $this->systemLogModel->getLogsByAction($action);
    }

    public function addLog($employeeId, $action, $details, $ipAddress = null) {
        return $this->systemLogModel->addLog($employeeId, $action, $details, $ipAddress);
    }
}
?>

