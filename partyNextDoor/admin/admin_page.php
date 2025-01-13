<?php
session_start();

// Vérifier si l'utilisateur est connecté et rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

<style>body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}
.dashboard {
    padding: 20px;
    background-color: #fff;
    margin: 50px auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 800px;
}
.dashboard h1 {
    text-align: center;
    color: #333;
}
.logout-btn, .editor-btn {
    display: block;
    width: 200px;
    margin: 20px auto;
    padding: 10px;
    text-align: center;
    border-radius: 4px;
    text-decoration: none;
    font-size: 16px;
}
.logout-btn {
    background-color: #ff4d4d;
    color: white;
}
.logout-btn:hover {
    background-color: #ff3333;
}
.editor-btn {
    background-color: white;
    border: 2px solid #4CAF50;
    color: #4CAF50;
}
.editor-btn:hover {
    background-color: #f1f1f1;
}</style>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <div class="dashboard">
        <h1>Bienvenue sur le Tableau de Bord Admin</h1>
        <p>Seuls les administrateurs peuvent voir cette page.</p>
        
        <a href="politique-editeur.php" class="editor-btn">Accéder à l'éditeur de politique</a>
        <a href="supprimer-utilisateurs.php" class="editor-btn">Supprimer des utilisateurs</a>
        <!-- Des boutons à ajouter pour d'autres fonctionnalités (comme supprime les evenements...) -->
        <a href="supprimer-event.php" class="editor-btn">Supprimer les evenements</a>
        <a href="envois-mails.php " class="editor-btn">Envois de mail</a>
         
        <a href="admin_login.php?logout=true" class="logout-btn">Se déconnecter</a>
        
    </div>

</body>
</html>
