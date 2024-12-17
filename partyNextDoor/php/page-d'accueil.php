<?php
// Connexion à la base de données
$host = 'localhost'; // Remplacez par votre hôte
$dbname = 'partynextdoor'; // Nom de votre base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Gestion des requêtes POST pour insérer des données dynamiques
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['event_title'], $_POST['event_venue'], $_POST['event_date'], $_POST['event_price'], $_POST['event_tags'])) {
        $title = $_POST['event_title'];
        $venue = $_POST['event_venue'];
        $date = $_POST['event_date'];
        $price = $_POST['event_price'];
        $tags = $_POST['event_tags'];

        $sql = "INSERT INTO events (title, venue, date, price, tags) VALUES (:title, :venue, :date, :price, :tags)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':title' => $title,
            ':venue' => $venue,
            ':date' => $date,
            ':price' => $price,
            ':tags' => $tags
        ]);

        echo "Événement ajouté avec succès !";
    } else {
        echo "Données incomplètes pour ajouter un événement.";
    }
}

// Récupération des événements depuis la base de données
$sql = "SELECT * FROM events ORDER BY date ASC";
$stmt = $pdo->query($sql);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PartyNextDoor - Événements</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .event-card {
            border: 1px solid #ddd;
            padding: 16px;
            margin-bottom: 16px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Liste des événements</h1>
    <div>
        <?php foreach ($events as $event): ?>
            <div class="event-card">
                <h2><?= htmlspecialchars($event['title']) ?></h2>
                <p><strong>Lieu :</strong> <?= htmlspecialchars($event['venue']) ?></p>
                <p><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
                <p><strong>Prix :</strong> <?= htmlspecialchars($event['price']) ?>€</p>
                <p><strong>Tags :</strong> <?= htmlspecialchars($event['tags']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Ajouter un nouvel événement</h2>
    <form method="POST">
        <label for="event_title">Titre :</label>
        <input type="text" id="event_title" name="event_title" required><br>

        <label for="event_venue">Lieu :</label>
        <input type="text" id="event_venue" name="event_venue" required><br>

        <label for="event_date">Date :</label>
        <input type="datetime-local" id="event_date" name="event_date" required><br>

        <label for="event_price">Prix :</label>
        <input type="number" id="event_price" name="event_price" required><br>

        <label for="event_tags">Tags :</label>
        <input type="text" id="event_tags" name="event_tags" required><br>

        <button type="submit">Ajouter l'événement</button>
    </form>
</body>
</html>
