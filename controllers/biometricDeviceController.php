<?php
require_once('../models/biometricDeviceModel.php');
require_once('../models/systemLogModel.php');

class BiometricDeviceController {
    private $biometricDeviceModel;
    private $systemLogModel;

    public function __construct($con) {
        $this->biometricDeviceModel = new BiometricDeviceModel($con);
        $this->systemLogModel = new SystemLogModel($con);
    }

    public function addDevice($serial, $description, $location, $adminId) {
        $result = $this->biometricDeviceModel->addDevice($serial, $description, $location);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Add Biometric Device', "Added device: $serial", $ipAddress);
        }
        return $result;
    }
     public function updateDevice($deviceId, $serial, $description, $location, $adminId) {
        $result = $this->biometricDeviceModel->updateDevice($deviceId, $serial, $description, $location);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Update Biometric Device', "Updated device ID: $deviceId", $ipAddress);
        }
        return $result;
    }

    public function deleteDevice($deviceId, $adminId) {
        $result = $this->biometricDeviceModel->deleteDevice($deviceId);
        if ($result) {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            $this->systemLogModel->addLog($adminId, 'Delete Biometric Device', "Deleted device ID: $deviceId", $ipAddress);
        }
        return $result;
         }

    public function getDevices() {
        return $this->biometricDeviceModel->getDevices();
    }
}
?>