<?php
$host = 'localhost';
$dbname = 'bddpartynextdoor';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Configure l'encodage UTF-8
$conn->set_charset("utf8mb4");

// Définit la variable pour le nom de la page
$page_name = "Conditions d'utilisation";

$sql = "SELECT content FROM multiple_content WHERE page_name = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Associe le paramètre à la requête préparée
$stmt->bind_param("s", $page_name);

// Exécute la requête
$stmt->execute();

// Associe le résultat
$stmt->bind_result($content);
$stmt->fetch();
$stmt->close();
$conn->close();

// Définit un contenu par défaut si aucun résultat trouvé
if (empty($content)) {
    $content = "<p>Content not available.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions d'utilisation</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/mentions-legales.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <header class="header">
        <h1>Conditions d'utilisation</h1>
    </header>
    <main class="content">
        <?php echo $content; ?>
    </main>
</body>
</html>

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
