<?php
// Connexion à la base de données
$host = 'localhost'; // Remplacez par votre hôte
$dbname = 'partynextdoortest'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Obtenir l'ID de l'utilisateur (exemple avec une session utilisateur)
session_start();
$userId = $_SESSION['user_id'] ?? 20; // Remplacez par la gestion de session appropriée

// Requête pour récupérer les informations de l'utilisateur
$sql = "SELECT nom_utilisateur, email, nom_de_famille, prenom, bio FROM utilisateur WHERE id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARTYNEXTDOOR - Profil</title>
    <link rel="stylesheet" href="css/profil.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    
</head>
<body>

<nav>
    <div class="logo">
        <img src="image/PND.png" alt="Logo">
    </div>
    <button class="settings-toggle" onclick="toggleSettings()">⚙️</button>
</nav>

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
                    <button class="ticket-button" onclick="viewTicket('ticket1')">
                        <span class="ticket-icon">🎟️</span>
                        <span class="ticket-text">Concert Rock - 15/07/2023</span>
                    </button>
                    <button class="ticket-button" onclick="viewTicket('ticket2')">
                        <span class="ticket-icon">🎟️</span>
                        <span class="ticket-text">Festival Électro - 22/08/2023</span>
                    </button>
                    <button class="ticket-button" onclick="viewTicket('ticket3')">
                        <span class="ticket-icon">🎟️</span>
                        <span class="ticket-text">Concert Pop - 10/09/2023</span>
                    </button>
                </div>
                <h2>FAVORIS</h2>
                <div class="favorites">
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #4CAF50;">🎭</div>
                        <span>La Gaîtière</span>
                    </div>
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #F44336;">💖</div>
                        <span>HeartDisco</span>
                    </div>
                    <div class="favorite-item">
                        <div class="favorite-icon" style="background-color: #3F51B5;">🎸</div>
                        <span>Rock In Scene</span>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <p>INVITE TES AMIS À FAIRE LA FÊTE AVEC TOI!</p>
        </footer>
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
        function toggleSettings() {
            const menu = document.getElementById('settingsMenu');
            menu.classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('settingsMenu');
            const toggle = document.querySelector('.settings-toggle');
            
            if (!menu.contains(event.target) && event.target !== toggle) {
                menu.classList.remove('active');
            }
        });

        function viewTicket(ticketId) {
            // This function will be called when a ticket button is clicked
            alert(`Affichage du billet: ${ticketId}`);
            // You can replace this with actual ticket viewing logic
        }

        function redirectToModifyProfile() {
            window.location.href = 'modify_profile.html';
        }
    </script>

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

</body>
</html>




