<?php
class DashboardModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Add methods here if you need to fetch any dashboard data from database
    // For example: getAdminStats(), getRecentActivities(), etc.
} 