<?php
include 'database/db_conn.php';

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
    $content = "<p>Contenu non disponible.</p>";
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

<?php 
include 'navigation/footer.php';
?>

</body>
</html>
