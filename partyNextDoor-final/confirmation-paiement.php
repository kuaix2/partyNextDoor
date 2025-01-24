<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;

if ($ticket_id > 0) {
    
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
    <link rel="stylesheet" href="css/confirmation_paiement.css">
</head>
<body>
<div class="container">
    <h1>Confirmation de Paiement</h1>
    <p class="thank-you">Merci pour votre paiement !</p>
    <div class="ticket-info">
    <p><strong>Événement :</strong> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
    <p><strong>Date :</strong> <?php echo htmlspecialchars($ticket['event_date']); ?></p>
    <p><strong>Prix :</strong> <?php echo number_format($ticket['event_price'], 2, ',', ''); ?> €</p>
    <p><strong>ID du billet :</strong> <?php echo htmlspecialchars($ticket['ticket_id']); ?></p>
    <p><strong>Nom de l'utilisateur :</strong> <?php echo htmlspecialchars($ticket['nom_utilisateur']); ?></p>
    </div>
    <div class="button-container">
            <a href="tous-les-events.php" class="btn">Retour aux événements</a>
        </div>
    </div>
</body>
</html>
