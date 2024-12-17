<?php
// Connexion à la base de données
$servername = "localhost";
$username = "fso";
$password = "fso";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les événements depuis la base de données
$sql = "SELECT * FROM events ORDER BY event_date DESC LIMIT 3";
$result = $conn->query($sql);

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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="partynextdoor/page-d'acceuil.html">

    <title>PartyNextDoor</title>
</head>
<body>
    <!-- Barre de navigation -->
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo"><img src="image/PND.png" alt="Logo"></a>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un événement, artiste ou lieu">
            </div>
            <div class="menu-burger">
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-icon"></div>
                <div class="menu-dropdown">
                    <a href="#" class="menu-item">Mon profil</a>
                    <a href="#" class="menu-item">Je suis organisateur</a>
                    <a href="#" class="menu-item">Festivals</a>
                    <a href="#" class="menu-item">Concerts</a>
                    <a href="#" class="menu-item">Soirées</a>
                    <a href="#" class="menu-item">Tous les événements</a>
                    <a href="#" class="menu-item">FAQ</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Première section -->
    <div class="section">
        <div class="container">
            <h1>PARTYNEXTDOOR</h1>
            <h2>TON GUIDE ULTIME</h2>
            <p>TROUVE TA SOIRÉE SELON TES ENVIES ET CRÉE-TOI DES SOUVENIRS POUR LA VIE</p>
            <button class="btn">Voir plus d'événements</button>
        </div>
        <video class="promo-video" autoplay muted loop>
            <source src="image/vidéo.mov" type="video/mp4">
            Votre navigateur ne supporte pas les vidéos HTML5.
        </video>
    </div>

    <!-- Section des événements -->
    <section class="events" id="events">
        <div class="events-header">
            <h2>ÉVÉNEMENTS À LA UNE</h2>
            <div class="location">PARIS</div>
        </div>
        <div class="events-grid">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <a href="page-evenement.php?id=<?php echo $event['id']; ?>" class="event-card">
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

    <!-- Deuxième section -->
    <div class="section-2">
        <div class="container">
            <h1 class="large">CONCERTS</h1>
            <h2 class="medium">SOIRÉES</h2>
            <div class="highlight">
                <h1 class="large">FESTIVALS</h1>
            </div>
        </div>
    </div>

    <!-- Troisième section -->
    <div class="section-3">
        <div class="container">
            <h1>VOUS ORGANISEZ UN ÉVÉNEMENT ?<br> TROUVEZ VOTRE PUBLIC !</h1>
            <p>Vendez vos billets à la bonne personne, au bon moment,<br> avec le bon message, au bon prix et via le bon canal.</p>
            <a href="#" class="button">PUBLIER MON ÉVÉNEMENT</a>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Défilement vers les événements
        document.querySelector('.btn').addEventListener('click', function(e) {
            e.preventDefault();
            const eventsSection = document.getElementById('events');
            eventsSection.scrollIntoView({ behavior: 'smooth' });
        });

        // Menu burger
        const burgerMenuButton = document.querySelector('.menu-burger');
        const burgerMenu = document.querySelector('.menu-dropdown');

        burgerMenuButton.onclick = function() {
            burgerMenu.classList.toggle('open');
        };
    </script>
</body>
</html>
