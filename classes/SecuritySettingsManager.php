<?php
class SecuritySettingsManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getSecuritySettings($user_id) {
        try {
            $sql = "SELECT * FROM user_security_settings WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$settings) {
                $this->createDefaultSettings($user_id);
                return $this->getSecuritySettings($user_id);
            }
            return $settings;
        } catch(Exception $e) {
            return false;
        }
    }

    public function updateSecuritySettings($user_id, $data) {
        try {
            $sql = "UPDATE user_security_settings SET
                    login_alerts = :login_alerts,
                    session_timeout = :session_timeout,
                    login_attempts_limit = :login_attempts_limit,
                    ip_restriction = :ip_restriction,
                    allowed_ips = :allowed_ips,
                    updated_at = NOW()
                    WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':login_alerts', $data['login_alerts']);
            $stmt->bindParam(':session_timeout', $data['session_timeout']);
            $stmt->bindParam(':login_attempts_limit', $data['login_attempts_limit']);
            $stmt->bindParam(':ip_restriction', $data['ip_restriction']);
            $stmt->bindParam(':allowed_ips', $data['allowed_ips']);

            if($stmt->execute()) {
                return ['success' => true, 'message' => 'Security settings updated'];
            }
            return ['success' => false, 'message' => 'Failed to update security settings'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function createDefaultSettings($user_id) {
        $sql = "INSERT INTO user_security_settings (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }
}
?>