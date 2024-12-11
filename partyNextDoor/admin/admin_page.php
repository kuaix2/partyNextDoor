<?php
session_start();

// Vérifier si l'utilisateur est connecté et rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>

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
        <!-- Des boutons à ajouter pour d'autres fonctionnalités (comme supprime les evenements...) -->
         
        <a href="admin_login.php?logout=true" class="logout-btn">Se déconnecter</a>
        
    </div>

</body>
</html>
