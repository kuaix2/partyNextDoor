<?php
class EventListView {
    public function render($events = []) {
        ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Événements</title>
    <link rel="stylesheet" href="assets/css/event_list.css">
</head>
<body>
    <div class="event-manager">
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
                    <th>Description</th>
                    <th>Places Disponibles</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= $event['id'] ?></td>
                        <td><?= htmlspecialchars($event['event_name']) ?></td>
                        <td><?= htmlspecialchars($event['event_adresse'] ?? "Non spécifiée") ?></td>
                        <td><?= $event['event_date'] ?></td>
                        <td><?= number_format($event['event_price'], 2) ?> €</td>
                        <td><?= htmlspecialchars($event['event_tags'] ?? "Aucun") ?></td>
                        <td><?= htmlspecialchars($event['event_description'] ?? "Aucune description") ?></td>
                        <td><?= $event['places_available'] ?? "Non spécifié" ?></td>
                        <td>
                            <a href="?delete=<?= $event['id'] ?>" class="delete-btn" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
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