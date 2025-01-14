<?php
include 'database/db_conn.php';

// Prend les contenus des mentions légales dans la base de données
$page_name = 'Mentions légales';
$sql = "SELECT content FROM multiple_content WHERE page_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $page_name);
$stmt->execute();
$stmt->bind_result($content);
$stmt->fetch();
$stmt->close();
$conn->close();


if (empty($content)) {
    $content = "<p>Contenu non disponible.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mentions-legales.css">
    <link rel="stylesheet" href="css/footer.css">
    <!-- Icônes FontAwesome -->
<!-- Lien CDN officiel de FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header class="header">
        <a href="accueil.php" class="home-icon" title="Retour à l'accueil">
            <i class="fas fa-home"></i>
        </a>
        <h1>Mentions légales</h1>
    </header>
    <main class="content">
        <?php echo $content; ?>
    </main>
</body>
</html>

<?php 
include 'navigation/footer.php';
?>

</body>
</html>
