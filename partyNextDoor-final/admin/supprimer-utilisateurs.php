<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Add this line to include PHPMailer
require 'vendor/autoload.php';

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
require_once 'models/UserModel.php';
require_once 'controllers/UserController.php';
require_once 'views/user_list_view.php';

// Initialize MVC components
$model = new UserModel($conn);
$controller = new UserController($model);
$view = new UserListView();

// Check authentication
$controller->checkAuth();

// Handle user deletion and warning emails
$message = '';
if (isset($_GET['warning'])) {
    $message = $controller->handleWarningEmail($_GET);
}
$controller->handleDelete($_GET);

// Get all users
$users = $controller->getAllUsers();

// Display the view
echo $view->render($users, $message);
