<?php
include 'database/db_conn.php';

// Récupérer la requête de recherche si présente
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Si une requête est fournie, rechercher dans la base de données
if ($searchQuery) {
    // Préparer la requête SQL pour la recherche
    $sql = "SELECT * FROM events WHERE event_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%"; // Utilisation de joker pour la recherche partielle
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Si aucune recherche, récupérer tous les événements
    $sql = "SELECT * FROM events ORDER BY event_date DESC";
    $result = $conn->query($sql);
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
    <title>PartyNextDoor - Guide des soirées et festivals</title>
    <link rel="stylesheet" href="css/cookies.css">
    <link rel="stylesheet" href="css/acu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@1,300&display=swap" rel="stylesheet">
</head>
<body>
    <div class="gradient-shape"></div>
    <div class="gradient-shape-2"></div>

    <header class="header">
        <div class="header-content">
            <a href="/" class="logo"><img src="image/PND.png" ></a>
            <div class="header-buttons">
                <a href="connexion.php" class="btn btn-outline">Se connecter</a>
                <a href="inscription.html" class="btn btn-primary">S'inscrire</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Bienvenue sur PartyNextDoor</h1>
            <p>ton guide ultime pour toutes les soirées et festivals en France!</p>
            <a href="#events" class="cta-button" id="learn-more">EN SAVOIR PLUS</a>
            <div class="app-preview">
                <img src="image/phone.webp" alt="PartyNextDoor App" style="background: #86858544; padding: 20px;">
            </div>
        </div>
    </section>

    <section class="events" id="events">

        <section class="events" id="events">
            <div class="events-header">
                <h2>ÉVÉNEMENTS À LA UNE</h2>
                <a href="tous-les-events.php" class="btn-voir-plus">Voir plus</a>
            </div>
            <div class="events-grid">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <a href="connexion.php">
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
                    <p>Aucun événement disponible.</p>
                <?php endif; ?>
            </div>
        </section>
        
    
    </section>
    <div id="cookie-popup" class="cookie-popup">
        <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. En utilisant notre site, vous acceptez les cookies.</p>
        <button id="accept-cookies">Accepter</button>
        <button id="decline-cookies">Refuser</button>
    </div>

    <footer class="footer">
    <div class="footer-content">
        <div class="footer-nav">

            <div class="footer-section">
                <h4>DÉCOUVRIR</h4>
                <ul>
                    <li><a href="connexion.php">Concerts</a></li>
                    <li><a href="connexion.php">Soirées</a></li>
                    <li><a href="connexion.php">Festivals</a></li>
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
            <p class="copyright-text">© 2024 PartyNextDoor. Tous droits réservés.</p>
        </div>

    </div>
</footer>


    <script>
        document.getElementById('learn-more').addEventListener('click', function(e) {
            e.preventDefault();
            const eventsSection = document.getElementById('events');
            eventsSection.scrollIntoView({ behavior: 'smooth' });
        });
    </script>
    <script src="script/cookies.js"></script>
</body>


</html>