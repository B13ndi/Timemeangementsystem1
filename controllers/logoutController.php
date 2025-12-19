<?php

if (!class_exists('LogoutModel')) {
    require('../models/logoutModel.php');
}

class LogoutController {
    private $logoutModel;

    // Dependency injection: Pass the LogoutModel instance through the constructor
    public function __construct(LogoutModel $logoutModel) {
        $this->logoutModel = $logoutModel;
    }

    public function logout() {
        $this->logoutModel->performLogout();
        header('Location: ../views/login.php');
        exit();
    }
}