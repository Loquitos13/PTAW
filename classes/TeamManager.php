<?php
class TeamManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUserTeams($user_id) {
        try {
            $sql = "SELECT t.*, tm.role as user_role, tm.joined_at,
                    (SELECT COUNT(*) FROM team_members WHERE team_id = t.id) as member_count
                    FROM teams t
                    JOIN team_members tm ON t.id = tm.team_id
                    WHERE tm.user_id = :user_id
                    ORDER BY t.created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return [];
        }
    }

    public function createTeam($user_id, $name, $description = '') {
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO teams (name, description, owner_id) VALUES (:name, :description, :owner_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':owner_id', $user_id);
            $stmt->execute();

            $team_id = $this->conn->lastInsertId();

          
            $sql = "INSERT INTO team_members (team_id, user_id, role) VALUES (:team_id, :user_id, 'owner')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':team_id', $team_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $this->conn->commit();
            return ['success' => true, 'message' => 'Team created successfully', 'team_id' => $team_id];
        } catch(Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function inviteTeamMember($team_id, $user_id, $email) {
        try {
           
            $sql = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $target_user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$target_user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            $sql = "SELECT id FROM team_members WHERE team_id = :team_id AND user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':team_id', $team_id);
            $stmt->bindParam(':user_id', $target_user['id']);
            $stmt->execute();

            if($stmt->fetch()) {
                return ['success' => false, 'message' => 'User is already a team member'];
            }

            
            $sql = "INSERT INTO team_members (team_id, user_id, role) VALUES (:team_id, :user_id, 'member')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':team_id', $team_id);
            $stmt->bindParam(':user_id', $target_user['id']);
            $stmt->execute();

            return ['success' => true, 'message' => 'Team member added successfully'];
        } catch(Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

?>
