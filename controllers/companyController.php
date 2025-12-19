<?php
require_once('../models/companyModel.php');
require_once('../models/systemLogModel.php');

class CompanyController {
    private $companyModel;
    private $systemLogModel;

    public function __construct($con) {
        $this->companyModel = new CompanyModel($con);
        $this->systemLogModel = new SystemLogModel($con);
    }

    public function addCompany($name, $adminId) {
        $result = $this->companyModel->addCompany($name);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Add Company', "Added company: $name", $ipAddress);
        }
        return $result;
    }
    public function updateCompany($companyId, $name, $adminId) {
        $result = $this->companyModel->updateCompany($companyId, $name);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Update Company', "Updated company ID: $companyId", $ipAddress);
        }
        return $result;
    }

    public function deleteCompany($companyId, $adminId) {
        $result = $this->companyModel->deleteCompany($companyId);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Delete Company', "Deleted company ID: $companyId", $ipAddress);
        }
        return $result;
    }
public function getCompanies() {
        return $this->companyModel->getCompanies();
    }
}
?>
