<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Supprimer les événements dont la date est dépassée
$currentDate = date("Y-m-d H:i:s");
$deleteExpiredEvents = "DELETE FROM events WHERE event_date < ?";
$stmt = $conn->prepare($deleteExpiredEvents);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$stmt->close();


// Récupérer la requête de recherche si présente
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';
$filterTag = isset($_GET['filter']) ? $_GET['filter'] : 'all'; // Récupérer le filtre sélectionné

// Si une requête est fournie, rechercher dans la base de données
if ($searchQuery) {
    $sql = "SELECT * FROM events WHERE event_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Si un filtre est appliqué, récupérer uniquement les événements correspondants
    if ($filterTag != 'all') {
        $sql = "SELECT * FROM events WHERE event_tags = ? ORDER BY event_date DESC"; // Utiliser event_tags
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $filterTag);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Si aucun filtre, récupérer tous les événements
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        $result = $conn->query($sql);
    }
}

// Récupérer les événements dans un tableau
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

    <!-- Barre de navigation -->
    <header class="header">
        <div class="header-content">
            <a href="accueil.php" class="logo"><img src="image/PND.png" alt="Logo"></a>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un évènement, artiste ou lieu" id="searchInput">
            </div>
            <div class="menu-burger">
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-dropdown">
                    <a href="tous-les-events.php?filter=festival" class="menu-item">Festivals</a>
                    <a href="tous-les-events.php?filter=concert" class="menu-item">Concerts</a>
                    <a href="tous-les-events.php?filter=soiree" class="menu-item">Soirées</a>
                    <a href="tous-les-events.php?filter=all" class="menu-item">Tous les évènements</a>
                    <a href="faq.html" class="menu-item">FAQ</a>
                </div>
            </div>
        </div>
    </header>

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

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-nav">
                <div class="footer-section">
                    <h4>DÉCOUVRIR</h4>
                    <ul>
                        <li><a href="tous-les-events.php?filter=concert">Concerts</a></li>
                        <li><a href="tous-les-events.php?filter=soiree">Soirées</a></li>
                        <li><a href="tous-les-events.php?filter=festival">Festivals</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>AIDE</h4>
                    <ul>
                        <li><a href="faq.html">FAQ</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>LÉGAL</h4>
                    <ul>
                        <li><a href="politique-condition-utilisation.php">Conditions d'utilisation</a></li>
                        <li><a href="politique-confidentialite.php">Politique de confidentialité</a></li>
                        <li><a href="politique-cookie.php">Cookies</a></li>
                        <li><a href="politique-mentions-legales.php">Mentions légales</a></li>
                    </ul>
                </div>
            </div>

            <div class="copyright">
                <p>© 2024 PartyNextDoor. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
