<?php
class EventController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    // Check if user is authenticated
    public function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: admin_login.php");
            exit();
        }
    }
    
    // Handle event deletion
    public function handleDelete($get_params) {
        if (isset($get_params['delete'])) {
            return $this->model->deleteEvent($get_params['delete']);
        }
        return false;
    }
    
    // Get all events
    public function getAllEvents() {
        return $this->model->getAllEvents();
    }
} 