<?php
require('includes/top.inc.php');
$name = '';
$email = '';
$employeeid = $_SESSION['USER_ID'];

$sql = "SELECT employee.*, department.departmentName
        FROM employee
        LEFT JOIN department ON employee.departmentId = department.departmentId
        WHERE employee.employeeid = '$employeeid'";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);

$name = $row['name'];
$email = $row['email'];
$departmentName = $row['departmentName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
</head>
<body>

    <div class="content pb-0">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><strong>Account Details</strong></div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" value="<?php echo $name; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Email</label>
                                <input type="email" value="<?php echo $email; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Department</label>
                                <input type="text" value="<?php echo $departmentName; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <a href="changePasswordView.php" class="btn btn-primary">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<?php
require('includes/footer.inc.php');
?>
