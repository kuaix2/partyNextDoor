<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID from the URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the event from the database
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);

$event = null;
if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/page-event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">  
    <link rel="stylesheet" href="css/header.css">
    
    <script src="script/deroulant-nav.js"></script>
    <title>Détails de l'Événement</title>
</head>
<body>
    <div class="gradient-shape"></div>
    <div class="gradient-shape-2"></div>

    <!-- Barre de navigation -->
    <header class="header">
        <div class="header-content">
            <a href="accueil.php" class="logo"><img src="image/PND.png" alt="Logo"></a>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un évènement, artiste ou lieu">
            </div>
            <div class="menu-burger">
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-dropdown">
                    <a href="profil.php" class="menu-item">Mon profil</a>
                    <a href="php/dashboard.php" class="menu-item">Je suis organisateur</a>
                    <a href="tous-les-events.php" class="menu-item">Festivals</a>
                    <a href="tous-les-events.php" class="menu-item">Concerts</a>
                    <a href="tous-les-events.php" class="menu-item">Soirées</a>
                    <a href="tous-les-events.php" class="menu-item">Tous les évènements</a>
                    <a href="faq.html" class="menu-item">FAQ</a>
                </div>
            </div>
        </div>
    </header>

    <?php if ($event): ?>
        <section class="event-detail">
            <div class="event-content">
                <div>
                    <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
                    <p><?php echo htmlspecialchars($event['event_adresse']); ?> - <?php echo date("d F Y | H:i", strtotime($event['event_date'])); ?></p>
                    <div class="event-info">
                        <span><strong>Prix :</strong> <?php echo number_format($event['event_price'], 2, ',', ''); ?>€</span>
                        <span><strong>Genres :</strong> <?php echo htmlspecialchars($event['event_tags']); ?></span>
                    </div>
                    <p>
                        <?php echo nl2br(htmlspecialchars($event['event_description'])); ?>
                    </p>

                    <div class="event-buttons">
                        <a href="#" class="btn btn-primary">Acheter Billet</a>
                        <?php
                        // Exemple de récupération des informations de l'événement depuis la base de données
                        $event_name = htmlspecialchars($event['event_name']);
                        $event_description = htmlspecialchars($event['event_description']);
                        $event_location = htmlspecialchars($event['event_adresse']);
                        $event_date_start = date("Ymd\THis\Z", strtotime($event['event_date'])); // Date de début formatée
                        $event_date_end = date("Ymd\THis\Z", strtotime($event['event_date'])); // Date de fin formatée (pour cet exemple, même date)
                        ?>

                        <a href="https://www.google.com/calendar/render?action=TEMPLATE&text=<?php echo urlencode($event_name); ?>&dates=<?php echo $event_date_start; ?>/<?php echo $event_date_end; ?>&details=<?php echo urlencode($event_description); ?>&location=<?php echo urlencode($event_location); ?>" class="btn btn-outline" target="_blank">
                            Ajouter au Calendrier
                        </a>

                    </div>
                </div>

                <?php if ($event['event_image']): ?>
                    <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Événement <?php echo htmlspecialchars($event['event_name']); ?>" class="event-image">
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <p>Événement introuvable.</p>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
    <div class="footer-content">
        <div class="footer-nav">

            <div class="footer-section">
                <h4>DÉCOUVRIR</h4>
                <ul>
                    <li><a href="tous-les-events.php">Concerts</a></li>
                    <li><a href="tous-les-events.php">Soirées</a></li>
                    <li><a href="tous-les-events.php">Festivals</a></li>
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
