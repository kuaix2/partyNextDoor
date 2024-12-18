<?php
// Connexion à la base de données
$host = 'localhost'; // Hôte de la base de données
$dbname = 'partynextdoor'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur
$password = ''; // Mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Récupérer les événements (limite de 3)
$query = "SELECT * FROM events LIMIT 3"; // Adaptez ce nom à votre table
$stmt = $pdo->query($query);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/page-d'accueil.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="#" class="logo" onclick="scrollToTop()"><img src="image/PND.png" alt="Logo"></a>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un évènement, artiste ou lieu">
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
                    <a href="#events" class="menu-item">Tous les évènements</a>
                    <a href="#" class="menu-item">FAQ</a>
                </div>
            </div>
        </div>
    </header>

    <div class="section">
        <div class="container">
            <h1>PARTYNEXTDOOR</h1>
            <h2>TON GUIDE ULTIME</h2>
            <p>TROUVE TA SOIRÉE SELON TES ENVIES ET CRÉE TOI DES SOUVENIRS POUR LA VIE</p>
            <button class="btn" onclick="window.location.href='#events'">Voir plus d'évènements</button>
        </div>
        <video class="promo-video" autoplay muted loop>
            <source src="image/vidéo.mov" type="video/mp4">
            Votre navigateur ne supporte pas les vidéos HTML5.
        </video>
    </div>

    <section class="events" id="events">
        <div class="events-header">
            <h2>ÉVÉNEMENTS À LA UNE</h2>
            <div class="location">PARIS</div>
        </div>
        <div class="events-grid">
            <?php foreach ($events as $event): ?>
                <a href="connexion.php" class="event-card">
                    <img src="image/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
                    <div class="event-content">
                        <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="event-venue"><?= htmlspecialchars($event['venue']) ?></p>
                        <div class="event-details">
                            <span><?= htmlspecialchars($event['date']) ?> | <?= htmlspecialchars($event['time']) ?></span>
                            <span><?= htmlspecialchars($event['price']) ?>€</span>
                        </div>
                        <div class="event-tags">
                            <span class="tag"><?= htmlspecialchars($event['tag']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
        // Fonction pour remonter en haut de la page
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
