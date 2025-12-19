<?php
require('../models/changePasswordModel.php');

class ChangePasswordController {
    private $model;

    public function __construct($con) {
        $this->model = new ChangePasswordModel($con);
    }

    public function processChangePassword($employeeId) {
        if (isset($_POST['change_password'])) {
            $newPassword = mysqli_real_escape_string($this->model->con, $_POST['new_password']);
            $confirmPassword = mysqli_real_escape_string($this->model->con, $_POST['confirm_password']);

            if ($newPassword === $confirmPassword) {
                $this->model->updatePassword($employeeId, $newPassword);
            } else {
                echo '<div class="alert alert-danger" role="alert">Passwords do not match. Please enter matching passwords.</div>';
            }
        }
    }
}
$changePasswordController = new ChangePasswordController($con);
$changePasswordController->processChangePassword($_SESSION['USER_ID']);
?>
