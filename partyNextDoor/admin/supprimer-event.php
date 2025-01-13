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
    $conn->query("DELETE FROM events WHERE id = $id");
}

// Récupération des événements
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
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
    <h1>Liste des Événements</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Date</th>
                <th>Prix</th>
                <th>Tags</th>
                <th>Image</th>
                <th>Description</th>
                <th>Places Disponibles</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                    <td><?= htmlspecialchars($row['event_adresse'] ?? "Non spécifiée") ?></td>
                    <td><?= $row['event_date'] ?></td>
                    <td><?= number_format($row['event_price'], 2) ?> €</td>
                    <td><?= htmlspecialchars($row['event_tags'] ?? "Aucun") ?></td>
                    <td>
                        <img src="<?= htmlspecialchars($row['event_image']) ?>" alt="Image de l'événement">
                    </td>
                    <td><?= htmlspecialchars($row['event_description'] ?? "Aucune description") ?></td>
                    <td><?= $row['places_available'] ?? "Non spécifié" ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">Supprimer</a>
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
