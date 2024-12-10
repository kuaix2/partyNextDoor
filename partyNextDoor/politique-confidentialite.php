<?php
// Database connection
$host = 'localhost';
$dbname = 'bddpartynextdoor';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize content variable for the entire document
$content = '';

// Fetch the existing content from the database
$sql = "SELECT content FROM content WHERE id = 1";  // Assuming you're fetching content with ID = 1
$result = $conn->query($sql);

// Default empty content if no data found
if ($result->num_rows > 0) {
    // Fetch the content from the database
    $row = $result->fetch_assoc();
    $content = $row['content'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Conditions</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/mentions-legales.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <header class="header">
        <h1>Politique de confidentialité</h1>
    </header>

    <main class="content">
        <?php echo $content; ?>  <!-- Display content from the database -->
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-nav">
                <div class="footer-section">
                    <h4>À PROPOS</h4>
                    <ul>
                        <li><a href="#">Notre Histoire</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Carrières</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>DÉCOUVRIR</h4>
                    <ul>
                        <li><a href="#">Événements</a></li>
                        <li><a href="#">Artistes</a></li>
                        <li><a href="#">Lieux</a></li>
                        <li><a href="#">Festivals</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>AIDE</h4>
                    <ul>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Billetterie</a></li>
                        <li><a href="#">Organisateurs</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h4>LÉGAL</h4>
                    <ul>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Cookies</a></li>
                        <li><a href="mentions-legales.html">Mentions légales</a></li>
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
