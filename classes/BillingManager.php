
<?php
class BillingManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getBillingInfo($user_id) {
        try {
            $sql = "SELECT * FROM billing_info WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $billing = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$billing) {
                $this->createDefaultBilling($user_id);
                return $this->getBillingInfo($user_id);
            }
            return $billing;
        } catch(Exception $e) {
            return false;
        }
    }

    public function getBillingHistory($user_id, $limit = 10) {
        try {
            $sql = "SELECT * FROM billing_history WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return [];
        }
    }

    public function updateBillingPlan($user_id, $plan_type, $billing_cycle) {
        try {
            $plans = [
                'free' => ['monthly' => 0, 'yearly' => 0],
                'basic' => ['monthly' => 9.99, 'yearly' => 99.99],
                'premium' => ['monthly' => 19.99, 'yearly' => 199.99],
                'enterprise' => ['monthly' => 49.99, 'yearly' => 499.99]
            ];

            $price = $plans[$plan_type][$billing_cycle] ?? 0;

            $sql = "UPDATE billing_info SET
                    plan_type = :plan_type,
                    billing_cycle = :billing_cycle,
                    price = :price,
                    updated_at = NOW()
                    WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':plan_type', $plan_type);
            $stmt->bindParam(':billing_cycle', $billing_cycle);
            $stmt->bindParam(':price', $price);

            if($stmt->execute()) {
                return ['success' => true, 'message' => 'Billing plan updated'];
            }
            return ['success' => false, 'message' => 'Failed to update billing plan'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function createDefaultBilling($user_id) {
        $sql = "INSERT INTO billing_info (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }
}
?>