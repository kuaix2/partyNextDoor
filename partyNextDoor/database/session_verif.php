<?php
session_start();

// Vérifier si l'utilisateur est connecté et rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: connexion.php");
    exit();
}
?>