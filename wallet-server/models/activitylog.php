<?php
class ActivityLog
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function logActivity($userId = null, $adminId = null, $action)
    {
        $sql = "INSERT INTO activity_logs (user_id, admin_id, user_action) VALUES (?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("iis", $userId, $adminId, $action);
        $stmt->execute();
    }

    // ✅ Get all activity logs (for admin panel)
    public function getAllLogs()
    {
        $sql = "SELECT activity_logs.id, activity_logs.action, activity_logs.created_at, 
                       users.email AS user_email, admins.email AS admin_email
                FROM activity_logs
                LEFT JOIN users ON activity_logs.user_id = users.id
                LEFT JOIN admins ON activity_logs.admin_id = admins.id
                ORDER BY activity_logs.created_at DESC";

        $result = $this->mysqli->query($sql);
        $logs = [];

        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }

        response(true, ["logs" => $logs]);
    }
}
?>
