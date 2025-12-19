<?php
class BiometricDeviceModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getDevices() {
        $res = mysqli_query($this->con, "SELECT * FROM biometric_device ORDER BY deviceId DESC");
        $devices = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $devices[] = $row;
        }
        return $devices;
    }

    public function getDeviceById($deviceId) {
        $deviceId = mysqli_real_escape_string($this->con, $deviceId);
        $res = mysqli_query($this->con, "SELECT * FROM biometric_device WHERE deviceId='$deviceId'");
        return mysqli_fetch_assoc($res);
    }

    public function addDevice($serial, $description, $location) {
        $serial = mysqli_real_escape_string($this->con, $serial);
        $description = mysqli_real_escape_string($this->con, $description);
        $location = mysqli_real_escape_string($this->con, $location);

        $sql = "INSERT INTO biometric_device(serial, description, location) VALUES ('$serial', '$description', '$location')";
        return mysqli_query($this->con, $sql);
    }

    public function updateDevice($deviceId, $serial, $description, $location) {
        $deviceId = mysqli_real_escape_string($this->con, $deviceId);
        $serial = mysqli_real_escape_string($this->con, $serial);
        $description = mysqli_real_escape_string($this->con, $description);
        $location = mysqli_real_escape_string($this->con, $location);

        $sql = "UPDATE biometric_device SET serial='$serial', description='$description', location='$location' WHERE deviceId='$deviceId'";
        return mysqli_query($this->con, $sql);
    }

    public function deleteDevice($deviceId) {
        $deviceId = mysqli_real_escape_string($this->con, $deviceId);
        return mysqli_query($this->con, "DELETE FROM biometric_device WHERE deviceId='$deviceId'");
    }
}
?>

