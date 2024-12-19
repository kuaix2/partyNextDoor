<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <title>PartyNextDoor</title>

    <!-- Importation des polices -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/accueil.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/cookies.css">

</head>
<body>
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

    <!-- Première section -->
    <div class="section">
        <div class="container">
            <h1>PARTYNEXTDOOR</h1>
            <h2>TON GUIDE ULTIME</h2>
            <p>TROUVE TA SOIRÉE SELON TES ENVIES ET CRÉE TOI DES SOUVENIRS POUR LA VIE</p>
            <a href="#events" class="btn" id="learn-more">Tous les événements</a>
        </div>
        <video class="promo-video" autoplay muted loop>
            <source src="image/vidéo.mov" type="video/mp4">
        </video>
    </div>

    </div> 


    <section class="events" id="events">
        <div class="events-header">
            <h2>ÉVÉNEMENTS À LA UNE</h2>
            <a href="tous-les-events.php" class="btn-voir-plus">Voir plus</a>
        </div>
        <div class="events-grid">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <a href="fiche-evenement.php?id=<?php echo $event['id']; ?>" class="event-card">
                        <?php if ($event['event_image']): ?>
                            <img src="php/<?php echo htmlspecialchars($event['event_image']); ?>" alt="Événement <?php echo htmlspecialchars($event['event_name']); ?>" class="event-image">
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

     <!-- Deuxième section -->
    <div class="section-2">
        <div class="container">
            
            <h1 class="large"><a href="tous-les-events.php" class="large">CONCERTS</a></h1>
            <h2 class="medium"><a href="tous-les-events.php" class="medium">SOIRÉES</a></h2>
            <h1 class="large"><a href="tous-les-events.php" class="large">FESTIVALS</a></h1>

        </div>
    </div>

    <!-- Troisième section -->
    <div class="section-3">
        <div class="container">
            <h1>VOUS ORGANISEZ UN ÉVÈNEMENT ?<br> TROUVEZ VOTRE PUBLIC !</h1>
            <p>Vendez vos billets à la bonne personne, au bon moment,<br> avec le bon message, au bon prix et via le bon canal.</p>
            <a href="php/dashboard.php" class="button">PUBLIER MON ÉVÈNEMENT</a>
        </div>
    </div>

    <div id="cookie-popup" class="cookie-popup">
        <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. En utilisant notre site, vous acceptez les cookies.</p>
        <button id="accept-cookies">Accepter</button>
        <button id="decline-cookies">Refuser</button>
    </div>

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
            <p class="copyright-text">© 2024 PartyNextDoor. Tous droits réservés.</p>
        </div>

    </div>
</footer>

<script>
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            let query = e.target.value; // Récupère la valeur de la barre de recherche
            if (query) {
                window.location.href = `search.php?q=${encodeURIComponent(query)}`; // Redirige vers la page de recherche avec la query
            }
        }
    });


    document.getElementById('learn-more').addEventListener('click', function(e) {
            e.preventDefault();
            const eventsSection = document.getElementById('events');
            eventsSection.scrollIntoView({ behavior: 'smooth' });
        });
</script>

<script src="script/cookies.js"></script>

