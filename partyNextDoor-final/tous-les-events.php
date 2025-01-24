<?php
include 'database/db_conn.php';

// Supprimer les événements dont la date est dépassée
$currentDate = date("Y-m-d H:i:s");
$deleteExpiredEvents = "DELETE FROM events WHERE event_date < ?";
$stmt = $conn->prepare($deleteExpiredEvents);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$stmt->close();



$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$filterTag = isset($_GET['filter']) ? $_GET['filter'] : 'all'; 


if ($searchQuery) {
    $sql = "SELECT * FROM events WHERE event_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    
    if ($filterTag != 'all') {
        $sql = "SELECT * FROM events WHERE event_tags = ? ORDER BY event_date DESC"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $filterTag);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        $result = $conn->query($sql);
    }
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
    <title>PartyNextDoor</title>
    <link rel="stylesheet" href="css/event.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <div class="gradient-shape"></div>
    <div class="gradient-shape-2"></div>

    <?php
    include 'navigation/header.php';
?>

    <!-- Section des événements -->
    <section class="events" id="events">
        <div class="filter-buttons">
            <a href="tous-les-events.php?filter=all" class="filter-button <?php echo ($filterTag == 'all' ? 'active' : ''); ?>">Tous</a>
            <a href="tous-les-events.php?filter=festival" class="filter-button <?php echo ($filterTag == 'festival' ? 'active' : ''); ?>">Festivals</a>
            <a href="tous-les-events.php?filter=soiree" class="filter-button <?php echo ($filterTag == 'soiree' ? 'active' : ''); ?>">Soirées</a>
            <a href="tous-les-events.php?filter=concert" class="filter-button <?php echo ($filterTag == 'concert' ? 'active' : ''); ?>">Concerts</a>
        </div>

        <div class="events-header">
            <h2>ÉVÉNEMENTS</h2>
        </div>
        <div class="events-grid">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <a href="fiche-evenement.php?id=<?php echo $event['id']; ?>" class="event-card">
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
                <p>Aucun événement trouvé pour "<?php echo htmlspecialchars($searchQuery); ?>"</p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    include 'navigation/footer.php';
?>
</body>
</html>
