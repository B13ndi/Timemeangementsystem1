<?php
require('includes/top.inc.php');

if ($_SESSION['role'] != 1) {
    header('location: profile.php');
    die();
}

require_once('../controllers/addDepartmentController.php');

$controller = new AddDepartmentController($con);

$departmentName = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['departmentName'])) {
        $departmentName = $_POST['departmentName'];
        $controller->addDepartment($departmentName);
    }
}
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Department</strong><small> Form</small></div>
                    <div class="card-body card-block">
                        <form method="post">
                            <div class="form-group">
                                <label for="departmentName" class="form-control-label">Department Name</label>
                                <input type="text" value="<?php echo $departmentName; ?>" name="departmentName" placeholder="Enter your department name" class="form-control" required>
                            </div> 
                            <button type="submit" class="btn btn-lg btn-info btn-block">
                                <span id="payment-button-amount">Submit</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require('includes/footer.inc.php');
?>
