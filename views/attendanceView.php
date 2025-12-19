<?php
require('includes/top.inc.php');
require_once('../models/attendanceModel.php');
require_once('../controllers/attendanceController.php');

$attendanceController = new AttendanceController($con);
$attendanceModel = new AttendanceModel($con);

$role = $_SESSION['role'];
$userId = $_SESSION['USER_ID'];
$departmentId = $_SESSION['USER_DEPARTMENT_ID'] ?? null;

// Clock In/Out handling
if (isset($_POST['clock_in'])) {
    $deviceId = isset($_POST['deviceId']) ? $_POST['deviceId'] : null;
    $attendanceController->clockIn($userId, $deviceId);
    header('location: attendanceView.php');
    die();
}

if (isset($_POST['clock_out'])) {
    $attendanceController->clockOut($userId);
    header('location: attendanceView.php');
    die();
}

// Get today's attendance
$todayAttendance = $attendanceModel->getTodayAttendance($userId);

// Get all attendance
$attendance = $attendanceController->getAttendance($role, $userId, $departmentId);
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Clock In/Out</strong></div>
                    <div class="card-body card-block">
                        <?php if (!$todayAttendance || !$todayAttendance['checkIn']): ?>
                            <form method="post">
                                <button type="submit" name="clock_in" class="btn btn-success btn-lg">
                                    <i class="fa fa-sign-in"></i> Clock In
                                </button>
                            </form>
                        <?php elseif ($todayAttendance['checkIn'] && !$todayAttendance['checkOut']): ?>
                            <p><strong>Clock In Time:</strong> <?php echo $todayAttendance['checkIn']; ?></p>
                            <form method="post">
                                <button type="submit" name="clock_out" class="btn btn-danger btn-lg">
                                    <i class="fa fa-sign-out"></i> Clock Out
                                </button>
                            </form>
                        <?php else: ?>
                            <p><strong>Clock In:</strong> <?php echo $todayAttendance['checkIn']; ?></p>
                            <p><strong>Clock Out:</strong> <?php echo $todayAttendance['checkOut']; ?></p>
                            <p class="text-success">You have completed your attendance for today.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><strong>Attendance History</strong></div>
                    <div class="card-body card-block">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <?php if ($role != 2): ?>
                                        <th>Department</th>
                                    <?php endif; ?>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($attendance) > 0): ?>
                                    <?php foreach ($attendance as $record): ?>
                                        <tr>
                                            <td><?php echo $record['date']; ?></td>
                                            <td><?php echo $record['name']; ?></td>
                                            <?php if ($role != 2): ?>
                                                <td><?php echo $record['departmentName'] ?? 'N/A'; ?></td>
                                            <?php endif; ?>
                                            <td><?php echo $record['checkIn'] ?? 'N/A'; ?></td>
                                            <td><?php echo $record['checkOut'] ?? 'N/A'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No attendance records found</td>
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

