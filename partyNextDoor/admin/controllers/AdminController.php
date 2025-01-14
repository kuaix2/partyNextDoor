<?php
class AdminController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function login($username, $password) {
        $admin = $this->model->getAdminByUsername($username);
        
        if ($admin && $password === "admin123") { // Note: In production, use proper password hashing
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            return true;
        }
        return false;
    }
    
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
} 