<?php
session_start();

// Load database configuration
$config = require_once 'config/database.php';

// Database connection
$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Include MVC files
require_once 'models/DashboardModel.php';
require_once 'controllers/DashboardController.php';
require_once 'views/admin_page_view.php';

// Initialize MVC components
$model = new DashboardModel($conn);
$controller = new DashboardController($model);
$view = new AdminPageView();

// Check authentication
$controller->checkAuth();

// Get dashboard data
$dashboardData = $controller->getDashboardData();

// Display the view
echo $view->render($dashboardData);
