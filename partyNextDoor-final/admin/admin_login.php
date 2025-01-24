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
require_once 'models/AdminModel.php';
require_once 'controllers/AdminController.php';
require_once 'views/admin_login_view.php';

// Initialize MVC components
$model = new AdminModel($conn);
$controller = new AdminController($model);
$view = new AdminLoginView();

$message = '';

// Handle logout
if (isset($_GET['logout'])) {
    $controller->logout();
    header("Location: admin_login.php");
    exit();
}

// Check if already logged in
if ($controller->isLoggedIn()) {
    header("Location: admin_page.php");
    exit();
}

// Handle login attempt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($controller->login($username, $password)) {
        header("Location: admin_page.php");
        exit();
    } else {
        $message = "Nom d'utilisateur ou mot de passe invalide !";
    }
}

// Display the view
echo $view->render($message);
