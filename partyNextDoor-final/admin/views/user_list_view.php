<?php
class UserListView {
    public function render($users = [], $message = '') {
        ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="assets/css/user_list.css">
</head>
<body>
    <div class="user-manager">
        <h1>Liste des utilisateurs inactifs</h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?= strpos($message, 'Erreur') !== false ? 'alert-error' : 'alert-success' ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom_Utilisateur</th>
                    <th>Email</th>
                    <th>Last_Activity</th>
                    <th>Photo de Profil</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nom_utilisateur']) ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? "Non spécifiée") ?></td>
                        <td><?= $user['last_activity'] ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($user['photo_profil']) ?>" alt="Photo de Profil de l'utilisateur">
                        </td>
                        <td class="actions">
                            <a href="?warning=<?= urlencode($user['email']) ?>" class="warning-btn" 
                               onclick="return confirm('Envoyer un avertissement à cet utilisateur ?');">
                                Avertir
                            </a>
                            <a href="?delete=<?= $user['id'] ?>" class="delete-btn" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="button-group">
            <button type="button" class="back-btn" onclick="window.location.href='admin_page.php';">
                Retour à la page d'administration
            </button>
        </div>
    </div>
</body>
</html>
<?php
        return ob_get_clean();
    }
} 