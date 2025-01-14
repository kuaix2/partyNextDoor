<?php
class UserModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Get all users ordered by last activity
    public function getAllUsers() {
        $sql = "SELECT * FROM utilisateur ORDER BY last_activity DESC";
        $result = $this->conn->query($sql);
        
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }
    
    // Delete a user by ID
    public function deleteUser($id) {
        $id = intval($id);
        $sql = "DELETE FROM utilisateur WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
    
    // Get user by email
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM Utilisateur WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $stmt->close();
        return $user;
    }
} 