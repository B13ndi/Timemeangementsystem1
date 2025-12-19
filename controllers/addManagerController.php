<?php
require('../models/addManagerModel.php');

class AddManagerController {
    private $model;

    public function __construct($con) {
        $this->model = new AddManagerModel($con);
    }