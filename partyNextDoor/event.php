<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "partynextdoortest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer tous les événements de la base de données
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

// Stockez les événements dans un tableau
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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/event.css">
</head>
<body>
    <div class="gradient-shape"></div>
    <div class="gradient-shape-2"></div>
    <header class="header">
        <div class="header-content">
            <a href="/" class="logo"><img src="image/PND.png" alt="PartyNextDoor Logo"></a>
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Rechercher un évènement, artiste ou lieu">
            </div>
            <div class="nav-bar">
                <div class="menu-button">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>
        </div>
    </header>
    <section class="events" id="events">
        <div class="events-header">
            <h2>ÉVÉNEMENTS</h2>
        </div>
        <div class="filter-buttons">
            <button class="filter-button active" data-filter="all">Tous</button>
            <button class="filter-button" data-filter="festival">Festivals</button>
            <button class="filter-button" data-filter="soiree">Soirées</button>
            <button class="filter-button" data-filter="concert">Concerts</button>
        </div>
        <div class="events-grid">
            <?php foreach ($events as $event): ?>
<a href="details.php?id=<?php echo htmlspecialchars($event['id']); ?>" class="event-card" data-type="<?php echo htmlspecialchars($event['event_tags']); ?>">
    <img src="<?php echo htmlspecialchars($event['event_image'] ?: 'image/default.jpg'); ?>" alt="Image de l'événement" class="event-image">
    <div class="event-content">
        <h3 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h3>
        <p class="event-venue"><?php echo htmlspecialchars($event['event_venue']); ?></p>
        <div class="event-details">
            <span><?php echo htmlspecialchars(date('D. d M | H:i', strtotime($event['event_date']))); ?></span>
            <span><?php echo htmlspecialchars($event['event_price']); ?>€</span>
        </div>
        <div class="event-tags">
            <span class="tag"><?php echo htmlspecialchars(strtoupper($event['event_tags'])); ?></span>
        </div>
    </div>
</a>
<?php endforeach; ?>


            <a href="connexion.html" class="event-card" data-type="soiree">
                <img src="image/event2.webp" alt="Événement 2" class="event-image">
                <div class="event-content">
                    <h3 class="event-title">Electric Nights</h3>
                    <p class="event-venue">La Machine du Moulin Rouge</p>
                    <div class="event-details">
                        <span>sam. 24 jan | 22:00</span>
                        <span>30€</span>
                    </div>
                    <div class="event-tags">
                        <span class="tag">SOIRÉES</span>
                        
                    </div>
                </div>
            </a>

            <a href="connexion.html" class="event-card" data-type="soiree">
                <img src="image/event3.jpg" alt="Événement 3" class="event-image">
                <div class="event-content">
                    <h3 class="event-title">Nuit Sonore</h3>
                    <p class="event-venue">Rex Club</p>
                    <div class="event-details">
                        <span>sam. 1 déc | 23:00</span>
                        <span>20€</span>
                    </div>
                    <div class="event-tags">
                        <span class="tag">SOIRÉES</span>
                        
                    </div>
                </div>
            </a>

            <a href="connexion.html" class="event-card" data-type="concert">
                <img src="image/event4.webp" alt="Événement 4" class="event-image">
                <div class="event-content">
                    <h3 class="event-title">Rock en Seine</h3>
                    <p class="event-venue">Domaine National de Saint-Cloud</p>
                    <div class="event-details">
                        <span>23-25 août | 12:00</span>
                        <span>69€</span>
                    </div>
                    <div class="event-tags">
                        <span class="tag">CONCERTS</span>
                        
                    </div>
                </div>
            </a>

            <a href="connexion.html" class="event-card" data-type="festival">
                <img src="image/event5.webp" alt="Événement 5" class="event-image">
                <div class="event-content">
                    <h3 class="event-title">Hellfest</h3>
                    <p class="event-venue">Clisson</p>
                    <div class="event-details">
                        <span>20-23 juin | 10:00</span>
                        <span>289€</span>
                    </div>
                    <div class="event-tags">
                        <span class="tag">FESTIVALS</span>
                    </div>
                </div>
            </a>

            <a href="connexion.html" class="event-card" data-type="concert">
                <img src="image/event6.jpg" alt="Événement 6" class="event-image">
                <div class="event-content">
                    <h3 class="event-title">Jazz à Vienne</h3>
                    <p class="event-venue">Théâtre Antique de Vienne</p>
                    <div class="event-details">
                        <span>28 juin - 13 juillet | 20:30</span>
                        <span>49€</span>
                    </div>
                    <div class="event-tags">
                        <span class="tag">CONCERTS</span>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-nav">
                <div class="footer-section">
                    <h4>À PROPOS</h4>
                    <ul>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>DÉCOUVRIR</h4>
                    <ul>
                        <li><a href="event.html#concerts" class="event-card" data-type="concert">CONCERTS</a></li>
                        <li><a href="event.html">SOIRÉES</a></li>
                        <li><a href="event.html">FESTIVALS</a></li>
                        
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>AIDE</h4>
                    <ul>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="faq.html">Support</a></li>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-button');
            const eventCards = document.querySelectorAll('.event-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    eventCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-type') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>