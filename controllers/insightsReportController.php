<?php
require_once('../models/insightsReportModel.php');
require_once('../models/systemLogModel.php');

class InsightsReportController {
    private $insightsReportModel;
    private $systemLogModel;

    public function __construct($con) {
        $this->insightsReportModel = new InsightsReportModel($con);
        $this->systemLogModel = new SystemLogModel($con);
    }

    public function getReports($managerId, $role) {
        if ($role == 1) {
            return $this->insightsReportModel->getAllReports();
        } else {
            return $this->insightsReportModel->getReports($managerId);
        }
    }

    public function addReport($managerId, $type, $period, $content) {
        $result = $this->insightsReportModel->addReport($managerId, $type, $period, $content);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($managerId, 'Create Report', "Created report type: $type", $ipAddress);
        }
        return $result;
    }

    public function deleteReport($reportId, $managerId) {
        $result = $this->insightsReportModel->deleteReport($reportId);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($managerId, 'Delete Report', "Deleted report ID: $reportId", $ipAddress);
        }
        return $result;
    }
}
?>