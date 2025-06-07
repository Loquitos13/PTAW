<?php
class NotificationSettingsManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getNotificationSettings($user_id) {
        try {
            $sql = "SELECT * FROM user_notification_settings WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$settings) {
                $this->createDefaultSettings($user_id);
                return $this->getNotificationSettings($user_id);
            }
            return $settings;
        } catch(Exception $e) {
            return false;
        }
    }

    public function updateNotificationSettings($user_id, $data) {
        try {
            $sql = "UPDATE user_notification_settings SET
                    email_notifications = :email_notifications,
                    sms_notifications = :sms_notifications,
                    push_notifications = :push_notifications,
                    security_alerts = :security_alerts,
                    marketing_emails = :marketing_emails,
                    weekly_reports = :weekly_reports,
                    system_updates = :system_updates,
                    team_invitations = :team_invitations,
                    billing_alerts = :billing_alerts,
                    updated_at = NOW()
                    WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':email_notifications', $data['email_notifications']);
            $stmt->bindParam(':sms_notifications', $data['sms_notifications']);
            $stmt->bindParam(':push_notifications', $data['push_notifications']);
            $stmt->bindParam(':security_alerts', $data['security_alerts']);
            $stmt->bindParam(':marketing_emails', $data['marketing_emails']);
            $stmt->bindParam(':weekly_reports', $data['weekly_reports']);
            $stmt->bindParam(':system_updates', $data['system_updates']);
            $stmt->bindParam(':team_invitations', $data['team_invitations']);
            $stmt->bindParam(':billing_alerts', $data['billing_alerts']);

            if($stmt->execute()) {
                return ['success' => true, 'message' => 'Notification settings updated'];
            }
            return ['success' => false, 'message' => 'Failed to update notification settings'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function createDefaultSettings($user_id) {
        $sql = "INSERT INTO user_notification_settings (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }
}

?>