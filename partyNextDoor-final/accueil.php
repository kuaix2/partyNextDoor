<?php

include 'php/user_conn_verif.php';
include 'database/db_conn.php';


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

include 'navigation/header.php';
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
    <link rel="stylesheet" href="css/cookies.css">

</head>
<body>


    <!-- Première section -->
    <div class="section">
        <div class="container">
            <h1>PARTYNEXTDOOR</h1>
            <h2>TON GUIDE ULTIME</h2>
            <h3>TROUVE TA SOIRÉE SELON TES ENVIES ET CRÉE TOI DES SOUVENIRS POUR LA VIE</h3>
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
                <p>Aucun événement disponible.</p>
            <?php endif; ?>
        </div>
        <div class="btn-wrapper">
        <a href="tous-les-events.php" class="btn-see-more">Voir plus</a>
    </div>
    </section>

     <!-- Deuxième section -->
    <div class="section-2">
        <div class="container">
        
            <h1 class="large"><a href="tous-les-events.php?filter=concert" class="filter-button <?php echo ($filterTag == 'concert' ? 'active' : ''); ?>">CONCERTS</a></h1>
            <h2 class="medium"><a href="tous-les-events.php?filter=soiree" class="filter-button <?php echo ($filterTag == 'soiree' ? 'active' : ''); ?>">SOIRÉES</a></h2>
            <h1 class="large"><a href="tous-les-events.php?filter=festival" class="filter-button <?php echo ($filterTag == 'festival' ? 'active' : ''); ?>">FESTIVALS</a></h1>

        </div>
    </div>

    <!-- Troisième section -->
    <div class="section-3">
        <div class="container">
            <h1>VOUS ORGANISEZ UN ÉVÈNEMENT ?<br> TROUVEZ VOTRE PUBLIC !</h1>
            <p>Vendez vos billets à la bonne personne, au bon moment,<br> avec le bon message, au bon prix et via le bon canal.</p>
            <a href="dashboard.php" class="button">PUBLIER MON ÉVÈNEMENT</a>
        </div>
    </div>

    <div id="cookie-popup" class="cookie-popup">
        <p>Nous utilisons des cookies pour améliorer votre expérience sur notre site. En utilisant notre site, vous acceptez les cookies.</p>
        <button id="accept-cookies">Accepter</button>
        <button id="decline-cookies">Refuser</button>
    </div>

<?php 
include 'navigation/footer.php';
?>
    
<script>
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            let query = e.target.value; // Récupère la valeur de la barre de recherche
            if (query) {
                window.location.href = `search.php?q=${encodeURIComponent(query)}`; // Redirige vers la page de recherche 
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

