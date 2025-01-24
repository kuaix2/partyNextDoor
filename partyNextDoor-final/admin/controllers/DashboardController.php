<?php
class DashboardController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: admin_login.php");
            exit();
        }
    }
    
    public function getDashboardData() {
        // Add logic here to get any dashboard data from model
        return [
            'username' => $_SESSION['admin_username'] ?? 'Admin'
        ];
    }
} 