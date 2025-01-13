<?php

// Obtenir l'ID de l'utilisateur (exemple avec une session utilisateur)
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour accéder à cette page."); // Vérifier si l'utilisateur est connecté
}

$userId = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur depuis la session


// Connexion à la base de données
$host = 'localhost'; // Remplacez par votre hôte
$dbname = 'bddpartynextdoor'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = 'root'; // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Requête pour récupérer les informations de l'utilisateur
$sql = "SELECT nom_utilisateur, email, nom_de_famille, prenom, bio FROM utilisateur WHERE id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

// Requête pour récupérer les billets de l'utilisateur connecté
$sqlTickets = "
    SELECT t.id AS ticket_id, e.event_name, e.event_date, e.event_adresse, e.event_price 
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = :userId
";
$stmtTickets = $pdo->prepare($sqlTickets);
$stmtTickets->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmtTickets->execute();
$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC); // Récupère tous les billets

// Supprimer les billets dont les événements sont passés
$sql = "
    DELETE t
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE e.event_date < CURDATE() AND t.user_id = :userId
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();

$sqlTickets = "
    SELECT t.id AS ticket_id, e.event_name, e.event_date, e.event_adresse, e.event_price 
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = :userId AND e.event_date >= CURDATE()
";

$stmtTickets = $pdo->prepare($sqlTickets);
$stmtTickets->bindParam(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtTickets->execute();
$tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);


?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARTYNEXTDOOR - Profil</title>
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="css/footer.css">
    
</head>
<body>

<?php 
include 'navigation/header.php';
?>

<div class="container">
    <main class="main-content">
        <section class="profile-section card">
            <h2>MON PROFIL</h2>
            <img src="image/mam.jpg" alt="Photo de profil" class="profile-photo">
            <div class="profile-info">
                <div class="info-group">
                    <label>NOM D'UTILISATEUR</label>
                    <p id="nom_utilisateur"><?php echo htmlspecialchars($user['nom_utilisateur']); ?></p>
                </div>
                <div class="info-group">
                    <label>ADRESSE MAIL</label>
                    <p id="email"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="info-group">
                    <label>NOM DE FAMILLE</label>
                    <p id="nom_de_famille"><?php echo htmlspecialchars($user['nom_de_famille']); ?></p>
                </div>
                <div class="info-group">
                    <label>PRÉNOM</label>
                    <p id="prenom"><?php echo htmlspecialchars($user['prenom']); ?></p>
                </div>
                <div class="info-group">
                        <label>BIO</label>
                        <p id="bio"><?php echo htmlspecialchars($user['bio']); ?></p>
                    </div>
            </div>
            <a href="modif_profil.php" class="button" onclick="redirectToModifyProfile()">MODIFIER MON PROFIL</a>
        </section>

        <section class="tickets-section card">
    <h2>MES BILLETS</h2>
    <div class="ticket-grid">
        <?php if (!empty($tickets)): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="ticket-item">
                    <button class="ticket-button">
                    <a href="generate_ticket_pdf.php?ticket_id=<?php echo $ticket['ticket_id']; ?>" class="btn-action" target="_blank">
    Télécharger
</a>

                        <span class="ticket-icon">🎟️</span>
                        <span class="ticket-text">
                            <?php echo htmlspecialchars($ticket['event_name']); ?> <br>
                            <?php echo date("d/m/Y", strtotime($ticket['event_date'])); ?> <br>
                            <?php echo htmlspecialchars($ticket['event_adresse']); ?> <br>
                            Prix : <?php echo number_format($ticket['event_price'], 2, ',', ''); ?> €
                        </span>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez pas encore acheté de billets.</p>
        <?php endif; ?>
    </div>

    <h2>FAVORIS</h2>
                <div class="favorites">
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #e9d4f2;">🎭</div>
                        <span>La Gaîtière</span>
                    </div>
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #f5ccd5;">💖</div>
                        <span>HeartDisco</span>
                    </div>
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #ff9e6a;">🎸</div>
                        <span>Rock In Scene</span>
                    </div>
                </div>
            </section>
        </main>

    </div>

    <!-- Settings overlay -->
    <div class="settings-overlay" id="settingsMenu">
    <button class="close-button" onclick="toggleSettings()">×</button>
    
    <div class="user-profile">
        <img src="image/mam.jpg" alt="Photo de profil">
        <span><?php echo htmlspecialchars($user['nom_utilisateur']); ?></span> <!-- Nom d'utilisateur ajouté ici -->
    </div>

    <ul class="menu-list">
        <li class="menu-item">Profil</li>
        <li class="menu-item">Event</li>
        <li class="menu-item">Organisateur</li>
        <a href="faq.html"><li class="menu-item">FAQ</li></a>
        <li class="menu-item">Partager l'application</li>
    </ul>

    <div class="contact-info">
        <p>contact@partynextdoor.com</p>
        <p>+33 6 XX XX XX XX</p>
    </div>
</div>


    <script>
        function viewTicket(ticketId) {
            // This function will be called when a ticket button is clicked
            alert(`Affichage du billet: ${ticketId}`);
            // You can replace this with actual ticket viewing logic
        }

        function redirectToModifyProfile() {
            window.location.href = 'modify_profile.html';
        }
    </script>

<?php 
include 'navigation/footer.php';
?>

</body>
</html>




