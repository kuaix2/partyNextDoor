<?php
session_start(); // Démarre la session

// Vérifier si l'utilisateur est connecté et rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// L'utilisateur est connecté, donc on continue d'afficher la page
?>

<?php
include '../database/db_conn.php';
// Suppression d'un événement si l'ID est passé en paramètre
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM utilisateur WHERE id = $id");
}

// Récupération d'utilisateur 
$result = $conn->query("SELECT * FROM utilisateur ORDER BY last_activity DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Événements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .delete-btn {
            color: #fff;
            background-color: #e74c3c;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h1>Liste des utilisateurs inactifs</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom_Utilisateur</th>
                <th>email</th>
                <th>Last_Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nom_utilisateur']) ?></td>
                    <td><?= htmlspecialchars($row['email'] ?? "Non spécifiée") ?></td>
                    <td><?= $row['last_activity'] ?></td>
                    <td>
                        <img src="<?= htmlspecialchars($row['photo_profil']) ?>" alt="Photo de Profil de l'utilisateur">
                    </td>
                   
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
