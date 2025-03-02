<?php
class Notification
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function createNotification($userId, $message)
    {
        $sql = "INSERT INTO notifications (user_id, notification_message) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $userId, $message);
        $stmt->execute();
    }

    public function viewNotifications($userId)
    {
        $sql = "SELECT id, notification_message, created_at FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            response(false, "No new notifications");
            return;
        }

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }

        response(true, ["notifications" => $notifications]);
    }
}
?>
