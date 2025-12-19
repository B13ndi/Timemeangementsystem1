<?php

require_once('../models/ManagerModel.php');
require_once('../views/ManagerView.php');

class ManagerController {
    private $managerModel;
    private $managerView;
    private $con;

    public function __construct($con) {
        $this->con = $con;
        require_once('../models/ManagerModel.php');
        require_once('../views/ManagerView.php');
        $this->managerModel = new ManagerModel($con);
        $this->managerView = new ManagerView();
    }

    public function handleRequest() {
        if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['employeeid'])) {
            $employeeId = mysqli_real_escape_string($this->con, $_GET['employeeid']);
            $this->managerModel->deleteManager($employeeId);
        }

        $managers = $this->managerModel->getManagers();
        $this->managerView->displayManagers($managers);
    }
}
