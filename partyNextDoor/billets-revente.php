<?php
include 'database/db_conn.php';

// Récupérer les billets en revente avec l'image de l'événement
$sql = "
    SELECT rt.id AS revente_id, e.event_name, e.event_date, e.event_image, rt.price AS revente_price, u.nom_utilisateur AS vendeur
    FROM revente_tickets rt
    JOIN tickets t ON rt.ticket_id = t.id
    JOIN events e ON t.event_id = e.id
    JOIN utilisateur u ON t.user_id = u.id
    WHERE rt.status = 'en_vente'
    ORDER BY e.event_date ASC";
$result = $conn->query($sql);

$billets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $billets[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billets en Revente</title>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/event.css">
</head>
<body>
    <div class="gradient-shape"></div>
    <div class="gradient-shape-2"></div>

    <!-- Barre de navigation -->
<?php
    include 'navigation/header.php';
?>

    <!-- Section des billets en revente -->
    <section class="events" id="events">
        <div class="events-header">
            <h2>Billets en Revente</h2>
        </div>

        <div class="events-grid">
            <?php if (!empty($billets)): ?>
                <?php foreach ($billets as $billet): ?>
                    <div class="event-card">
                        <?php if (!empty($billet['event_image'])): ?>
                            <img src="<?php echo htmlspecialchars($billet['event_image']); ?>" alt="Événement <?php echo htmlspecialchars($billet['event_name']); ?>" class="event-image">
                        <?php endif; ?>
                        <div class="event-content">
                            <h3 class="event-title"><?php echo htmlspecialchars($billet['event_name']); ?></h3>
                            <p class="event-venue"><?php echo date("D d M | H:i", strtotime($billet['event_date'])); ?></p>
                            <div class="event-details">
                                <span>Prix : <?php echo number_format($billet['revente_price'], 2, ',', ''); ?> €</span>
                                <span>Vendeur : <?php echo htmlspecialchars($billet['vendeur']); ?></span>
                            </div>
                            <div class="event-tags">
    <a href="paiement-revent.php?revente_id=<?php echo $billet['revente_id']; ?>" class="btn btn-outline">Acheter</a>
</div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun billet en revente pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    include 'navigation/footer.php';
?>
</body>
</html>

