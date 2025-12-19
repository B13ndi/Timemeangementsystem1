<?php
require('includes/top.inc.php');
require_once('../models/biometricDeviceModel.php');
require_once('../controllers/biometricDeviceController.php');

$biometricDeviceController = new BiometricDeviceController($con);
$role = $_SESSION['role'];
$userId = $_SESSION['USER_ID'];

// Only admin can manage devices
if ($role != 1) {
    header('location: profile.php');
    die();
}

// Add device
if (isset($_POST['add_device'])) {
    $serial = $_POST['serial'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $biometricDeviceController->addDevice($serial, $description, $location, $userId);
    header('location: biometricDeviceView.php');
    die();
}

// Delete device
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $biometricDeviceController->deleteDevice($_GET['id'], $userId);
    header('location: biometricDeviceView.php');
    die();
}

$devices = $biometricDeviceController->getDevices();
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Add Biometric Device</strong></div>
                    <div class="card-body card-block">
                        <form method="post">
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" name="serial" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                            <button type="submit" name="add_device" class="btn btn-primary">Add Device</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Biometric Devices</strong></div>
                    <div class="card-body card-block">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Serial</th>
                                    <th>Description</th>
                                    <th>Location</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($devices) > 0): ?>
                                    <?php foreach ($devices as $device): ?>
                                        <tr>
                                            <td><?php echo $device['deviceId']; ?></td>
                                            <td><?php echo htmlspecialchars($device['serial']); ?></td>
                                            <td><?php echo htmlspecialchars($device['description']); ?></td>
                                            <td><?php echo htmlspecialchars($device['location']); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($device['created_at'])); ?></td>
                                            <td>
                                                <a href="?delete=1&id=<?php echo $device['deviceId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No devices found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.inc.php'); ?>

