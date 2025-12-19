<?php
require('includes/top.inc.php');
require_once('../models/insightsReportModel.php');
require_once('../controllers/insightsReportController.php');

$insightsReportController = new InsightsReportController($con);
$role = $_SESSION['role'];
$userId = $_SESSION['USER_ID'];

// Only manager and admin can view/create reports
if ($role != 1 && $role != 3) {
    header('location: profile.php');
    die();
}

// Add report
if (isset($_POST['add_report'])) {
    $type = $_POST['type'];
    $period = $_POST['period'];
    $content = $_POST['content'];
    $insightsReportController->addReport($userId, $type, $period, $content);
    header('location: insightsReportView.php');
    die();
}

// Delete report
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $insightsReportController->deleteReport($_GET['id'], $userId);
    header('location: insightsReportView.php');
    die();
}

$reports = $insightsReportController->getReports($userId, $role);
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <?php if ($role == 3): ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Create Insights Report</strong></div>
                    <div class="card-body card-block">
                        <form method="post">
                            <div class="form-group">
                                <label>Report Type</label>
                                <input type="text" name="type" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Period (Date)</label>
                                <input type="date" name="period" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea name="content" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="add_report" class="btn btn-primary">Create Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Insights Reports</strong></div>
                    <div class="card-body card-block">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Period</th>
                                    <th>Manager</th>
                                    <th>Content</th>
                                    <th>Created At</th>
                                    <?php if ($role == 1 || $role == 3): ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($reports) > 0): ?>
                                    <?php foreach ($reports as $report): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($report['type']); ?></td>
                                            <td><?php echo $report['period']; ?></td>
                                            <td><?php echo htmlspecialchars($report['manager_name']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($report['content'], 0, 100)) . '...'; ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($report['created_at'])); ?></td>
                                            <?php if ($role == 1 || ($role == 3 && $report['manager_id'] == $userId)): ?>
                                                <td>
                                                    <a href="?delete=1&id=<?php echo $report['reportId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No reports found</td>
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

