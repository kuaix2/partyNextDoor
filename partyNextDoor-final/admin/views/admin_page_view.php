<?php
class AdminPageView {
    public function render($data = []) {
        ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
</head>
<body>
    <div class="dashboard">
        <h1>Bienvenue sur le Tableau de Bord Admin</h1>
        <p>Seuls les administrateurs peuvent voir cette page.</p>
        
        <a href="politique-editeur.php" class="editor-btn">Accéder à l'éditeur de politique</a>
        <a href="supprimer-utilisateurs.php" class="editor-btn">Supprimer des utilisateurs</a>
        <a href="supprimer-event.php" class="editor-btn">Supprimer les evenements</a>
        <a href="question.php" class="editor-btn">Question des utilisateurs</a>
         
        <a href="admin_login.php?logout=true" class="logout-btn">Se déconnecter</a>
    </div>
</body>
</html>
<?php
        return ob_get_clean();
    }
} 