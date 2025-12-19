<?php
class MedicalVerificationModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function addVerification($timeoffId, $doctorName, $reason, $medicalDocument) {
        $timeoffId = mysqli_real_escape_string($this->con, $timeoffId);
        $doctorName = mysqli_real_escape_string($this->con, $doctorName);
        $reason = mysqli_real_escape_string($this->con, $reason);
        $medicalDocument = mysqli_real_escape_string($this->con, $medicalDocument);

        $sql = "INSERT INTO medical_verification(timeoff_id, doctor_name, reason, medical_document) VALUES ('$timeoffId', '$doctorName', '$reason', '$medicalDocument')";
        return mysqli_query($this->con, $sql);
    }

    public function getVerificationByTimeOffId($timeoffId) {
        $timeoffId = mysqli_real_escape_string($this->con, $timeoffId);
        $res = mysqli_query($this->con, "SELECT * FROM medical_verification WHERE timeoff_id='$timeoffId'");
        return mysqli_fetch_assoc($res);
    }

    public function updateVerification($verificationId, $doctorName, $reason, $medicalDocument) {
        $verificationId = mysqli_real_escape_string($this->con, $verificationId);
        $doctorName = mysqli_real_escape_string($this->con, $doctorName);
        $reason = mysqli_real_escape_string($this->con, $reason);
        $medicalDocument = mysqli_real_escape_string($this->con, $medicalDocument);

        $sql = "UPDATE medical_verification SET doctor_name='$doctorName', reason='$reason', medical_document='$medicalDocument' WHERE verificationId='$verificationId'";
        return mysqli_query($this->con, $sql);
    }
}
?>

