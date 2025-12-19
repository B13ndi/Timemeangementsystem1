<?php
require('includes/top.inc.php');
require_once('../models/notificationModel.php');
require_once('../controllers/notificationController.php');

$notificationController = new NotificationController($con);
$userId = $_SESSION['USER_ID'];

// Mark as read
if (isset($_GET['mark_read']) && isset($_GET['id'])) {
    $notificationController->markAsRead($_GET['id']);
    header('location: notificationsView.php');
    die();
}

// Mark all as read
if (isset($_POST['mark_all_read'])) {
    $notificationController->markAllAsRead($userId);
    header('location: notificationsView.php');
    die();
}

// Delete notification
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $notificationController->deleteNotification($_GET['id']);
    header('location: notificationsView.php');
    die();
}

$notifications = $notificationController->getNotifications($userId);
$unreadCount = $notificationController->getUnreadCount($userId);
?>

<div class="content pb-0">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Notifications</strong>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge badge-danger"><?php echo $unreadCount; ?> unread</span>
                        <?php endif; ?>
                        <form method="post" style="display: inline;">
                            <button type="submit" name="mark_all_read" class="btn btn-sm btn-primary float-right">
                                Mark All as Read
                            </button>
                        </form>
                    </div>
                    <div class="card-body card-block">
                        <?php if (count($notifications) > 0): ?>
                            <div class="list-group">
                                <?php foreach ($notifications as $notification): ?>
                                    <div class="list-group-item <?php echo $notification['is_read'] == 0 ? 'list-group-item-warning' : ''; ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1"><?php echo htmlspecialchars($notification['subject']); ?></h5>
                                            <small><?php echo date('Y-m-d H:i', strtotime($notification['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-1" style="white-space: pre-wrap;"><?php echo htmlspecialchars($notification['body']); ?></p>
                                        <div>
                                            <?php if ($notification['is_read'] == 0): ?>
                                                <a href="?mark_read=1&id=<?php echo $notification['notificationId']; ?>" class="btn btn-sm btn-success">Mark as Read</a>
                                            <?php endif; ?>
                                            <a href="?delete=1&id=<?php echo $notification['notificationId']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center">No notifications</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.inc.php'); ?>

