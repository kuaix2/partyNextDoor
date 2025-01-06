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

// Récupérer l'ID de l'événement depuis l'URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête SQL avec jointure pour récupérer les informations de l'événement et de l'organisateur
$sql = "
    SELECT e.*, u.nom_utilisateur
    FROM events e
    LEFT JOIN utilisateur u ON e.user_id = u.id
    WHERE e.id = $event_id
";
$result = $conn->query($sql);

$event = null;
$organizer_name = '';
if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
    $organizer_name = $event['nom_utilisateur']; // Récupérer le nom de l'organisateur
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_ticket'])) {
    session_start(); // Démarrer la session si ce n'est pas déjà fait

    // Vérifiez si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vous devez être connecté pour acheter un billet.');</script>";
        header("Location: login.php"); // Rediriger vers la page de connexion
        exit();
    }

    $user_id = $_SESSION['user_id']; // Récupérer l'ID utilisateur depuis la session

    $conn->begin_transaction();

    // Sélection des places disponibles
    $stmt = $conn->prepare("SELECT places_available FROM events WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['places_available'] > 0) {
            $new_places = $row['places_available'] - 1;

            // Mise à jour des places disponibles
            $stmt = $conn->prepare("UPDATE events SET places_available = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_places, $event_id);
            $stmt->execute();

            // Enregistrement du billet
            $stmt = $conn->prepare("INSERT INTO tickets (user_id, event_id, price) VALUES (?, ?, ?)");
            $stmt->bind_param("iid", $user_id, $event_id, $event['event_price']);
            $stmt->execute();

            $conn->commit();
            echo "<script>alert('Votre billet a été acheté avec succès !');</script>";
        } else {
            $conn->rollback();
            echo "<script>alert('Désolé, il n\'y a plus de places disponibles pour cet événement.');</script>";
        }
    } else {
        $conn->rollback();
        echo "<script>alert('Erreur lors de l\'achat du billet.');</script>";
    }
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
                    <a href="dashboard.php" class="menu-item">Je suis organisateur</a>
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
                        <span><strong>Organisateur :</strong> <?php echo htmlspecialchars($organizer_name); ?></span>
                        <span><strong>Places restantes :</strong> <?php echo intval($event['places_available']); ?></span>

</div>


                    <p>
                        <?php echo nl2br(htmlspecialchars($event['event_description'])); ?>
                    </p>
                    
                    <div class="event-buttons">
                    <?php if ($event['places_available'] > 0): ?>
        <form method="POST" action="">
            <button type="submit" name="buy_ticket" class=" btn btn-primary">Acheter un billet</button>
        </form>
    <?php else: ?>
        <p style="color:red;">Événement complet !</p>
    <?php endif; ?>
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
