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

// Récupérer l'ID du billet depuis l'URL
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;

if ($ticket_id > 0) {
    // Récupérer les détails du billet
    $sql = "SELECT t.id AS ticket_id, e.event_name, e.event_date, e.event_price, u.nom_utilisateur
            FROM tickets t
            JOIN events e ON t.event_id = e.id
            JOIN utilisateur u ON t.user_id = u.id
            WHERE t.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
    } else {
        die("Billet introuvable.");
    }

    $stmt->close();
} else {
    die("Aucun ID de billet fourni.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Paiement</title>
</head>
<body>
    <h1>Confirmation de Paiement</h1>
    <p>Merci pour votre paiement !</p>
    <p><strong>Événement :</strong> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
    <p><strong>Date :</strong> <?php echo htmlspecialchars($ticket['event_date']); ?></p>
    <p><strong>Prix :</strong> <?php echo number_format($ticket['event_price'], 2, ',', ''); ?> €</p>
    <p><strong>ID du billet :</strong> <?php echo htmlspecialchars($ticket['ticket_id']); ?></p>
    <p><strong>Nom de l'utilisateur :</strong> <?php echo htmlspecialchars($ticket['nom_utilisateur']); ?></p>

    <a href="tous-les-events.php">Retour aux événements</a>
</body>
</html>
