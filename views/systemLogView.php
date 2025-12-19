<?php
require('includes/top.inc.php');
require_once('../models/systemLogModel.php');
require_once('../controllers/systemLogController.php');

$systemLogController = new SystemLogController($con);
$role = $_SESSION['role'];

// Only admin can view logs
if ($role != 1) {
    header('location: profile.php');
    die();
}

$logs = $systemLogController->getLogs(200);
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>System Logs</strong></div>
                    <div class="card-body card-block">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Date/Time</th>
                                    <th>Employee</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($logs) > 0): ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?php echo date('Y-m-d H:i:s', strtotime($log['action_time'])); ?></td>
                                            <td><?php echo $log['employee_name'] ?? 'System'; ?></td>
                                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                                            <td><?php echo htmlspecialchars($log['details']); ?></td>
                                            <td><?php echo $log['ip_address'] ?? 'N/A'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No logs found</td>
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

