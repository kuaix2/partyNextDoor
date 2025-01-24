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
require_once 'models/EventModel.php';
require_once 'controllers/EventController.php';
require_once 'views/event_list_view.php';

// Initialize MVC components
$model = new EventModel($conn);
$controller = new EventController($model);
$view = new EventListView();

// Check authentication
$controller->checkAuth();

// Handle event deletion
$controller->handleDelete($_GET);

// Get all events
$events = $controller->getAllEvents();

// Display the view
echo $view->render($events);
