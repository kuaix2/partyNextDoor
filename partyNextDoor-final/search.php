<?php

include 'database/db_conn.php';


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';


if ($searchQuery) {
    
    $sql = "SELECT * FROM events WHERE event_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%"; 
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    
    $sql = "SELECT * FROM events ORDER BY event_date DESC";
    $result = $conn->query($sql);
}


$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - PartyNextDoor</title>
    <link rel="stylesheet" href="css/event.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    
    <?php 
include 'navigation/header.php';
?>
    
    <section class="events" id="events">
        <div class="events-header">
            <h2>Résultats pour "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        </div>
        <div class="events-grid">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <a href="fiche-evenement.php?id=<?php echo $event['id']; ?>" class="event-card" data-type="<?php echo htmlspecialchars($event['event_tags']); ?>">
                        <?php if ($event['event_image']): ?>
                            <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Événement <?php echo htmlspecialchars($event['event_name']); ?>" class="event-image">
                        <?php endif; ?>
                        <div class="event-content">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p class="event-venue"><?php echo htmlspecialchars($event['event_adresse']); ?></p>
                            <div class="event-details">
                                <span><?php echo date("D d M | H:i", strtotime($event['event_date'])); ?></span>
                                <span><?php echo number_format($event['event_price'], 2, ',', ''); ?>€</span>
                            </div>
                            <div class="event-tags">
                                <span class="tag"><?php echo htmlspecialchars($event['event_tags']); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun événement trouvé pour "<?php echo htmlspecialchars($searchQuery); ?>".</p>
            <?php endif; ?>
        </div>
    </section>

    <?php 
include 'navigation/footer.php';
?>
</body>
</html>
