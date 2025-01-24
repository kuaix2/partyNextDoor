<?php
class AdminModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getAdminByUsername($username) {
        $username = $this->conn->real_escape_string($username);
        $sql = "SELECT * FROM admins WHERE username = '$username'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
} 