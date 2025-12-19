<?php
require_once('../models/notificationModel.php');

class NotificationController {
    private $notificationModel;

    public function __construct($con) {
        $this->notificationModel = new NotificationModel($con);
    }

    public function getNotifications($employeeId) {
        return $this->notificationModel->getNotifications($employeeId);
    }

    public function getUnreadCount($employeeId) {
        return $this->notificationModel->getUnreadCount($employeeId);
    }

    public function addNotification($sentTo, $subject, $body) {
        return $this->notificationModel->addNotification($sentTo, $subject, $body);
    }

    public function markAsRead($notificationId) {
        return $this->notificationModel->markAsRead($notificationId);
    }

    public function markAllAsRead($employeeId) {
        return $this->notificationModel->markAllAsRead($employeeId);
    }

    public function deleteNotification($notificationId) {
        return $this->notificationModel->deleteNotification($notificationId);
    }
}
?>

