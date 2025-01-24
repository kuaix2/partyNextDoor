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
require_once 'models/ContentEditorModel.php';
require_once 'controllers/ContentEditorController.php';
require_once 'views/content_editor_view.php';

// Initialize MVC components
$model = new ContentEditorModel($conn);
$controller = new ContentEditorController($model);
$view = new ContentEditorView();

// Check authentication
$controller->checkAuth();

// Handle form submission
$message = $controller->handleFormSubmission($_POST);

// Get all pages content
$contents = $controller->getAllPages();

// Display the view
echo $view->render($contents, $message);
