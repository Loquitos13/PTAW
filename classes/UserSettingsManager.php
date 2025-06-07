<?php
class UserSettingsManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function updateGeneralSettings($user_id, $data) {
        try {
            $sql = "UPDATE users SET
                    first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    updated_at = NOW()
                    WHERE id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':first_name', $data['first_name']);
            $stmt->bindValue(':last_name', $data['last_name']);
            $stmt->bindValue(':email', $data['email']);

            if($stmt->execute()) {
                $this->logActivity($user_id, 'general_settings_updated', 'Updated general settings');
                return ['success' => true, 'message' => 'Settings updated successfully'];
            }
            return ['success' => false, 'message' => 'Failed to update settings'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updatePassword($user_id, $current_password, $new_password) {
        try {
            $sql = "SELECT password FROM users WHERE id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$user || !password_verify($current_password, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }

            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':password', $new_hash);

            if($stmt->execute()) {
                $this->logActivity($user_id, 'password_changed', 'Password updated');
                return ['success' => true, 'message' => 'Password updated successfully'];
            }
            return ['success' => false, 'message' => 'Failed to update password'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUserData($user_id) {
        try {
            $sql = "SELECT id, username, email, first_name, last_name, role, status, created_at
                    FROM users WHERE id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return false;
        }
    }

    private function logActivity($user_id, $action, $description) {
        try {
           
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

            $sql = "INSERT INTO user_activity_log (user_id, action, description, ip_address, user_agent)
                    VALUES (:user_id, :action, :description, :ip_address, :user_agent)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':action', $action);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':ip_address', $ip_address);
            $stmt->bindValue(':user_agent', $user_agent);

            $stmt->execute();
        } catch(Exception $e) {
            
            error_log("Activity log error: " . $e->getMessage());
        }
    }
}
?>