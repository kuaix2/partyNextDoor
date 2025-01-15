<?php
include 'database/db_conn.php';
// Vérifiez si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Récupérer l'ID utilisateur depuis la session

// Requête pour récupérer les billets achetés par l'utilisateur
$sql = "
    SELECT t.id AS ticket_id, e.event_name, e.event_date, e.event_price
    FROM tickets t
    JOIN events e ON t.event_id = e.id
    WHERE t.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

$stmt->close();

// Traitement de la revente d'un billet
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ticket_id'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $price = floatval($_POST['price']);

    // Valider l'existence du billet pour cet utilisateur
    $stmt = $conn->prepare("SELECT id FROM tickets WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $ticket_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Ajouter le billet à la table des reventes
        $stmt = $conn->prepare("INSERT INTO revente_tickets (ticket_id, price, status) VALUES (?, ?, 'en_vente')");
        $stmt->bind_param("id", $ticket_id, $price);

        if ($stmt->execute()) {
            echo "<script>alert('Votre billet est maintenant en vente !');</script>";
        } else {
            echo "<script>alert('Erreur lors de la mise en vente du billet.');</script>";
        }
    } else {
        echo "<script>alert('Billet non valide pour cet utilisateur.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revendre un Billet</title>
    <link rel="stylesheet" href="css/revendre-billet.css">
</head>
<body>
    <h1>Revendre un Billet</h1>
    
    <?php if (!empty($tickets)): ?>
        <form action="revendre-billet.php" method="post">
            <label for="ticket_id">Sélectionnez un billet :</label>
            <select name="ticket_id" id="ticket_id" required>
                <?php foreach ($tickets as $ticket): ?>
                    <option value="<?php echo $ticket['ticket_id']; ?>">
                        <?php echo htmlspecialchars($ticket['event_name']) . " - " . date("d F Y", strtotime($ticket['event_date'])) . " - " . number_format($ticket['event_price'], 2, ',', '') . " €"; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div>
                <label for="price">Prix de revente (€) :</label>
                <input type="number" name="price" id="price" step="0.01" min="0" required>
            </div>

            <button type="submit">Mettre en Vente</button>
        </form>
    <?php else: ?>
        <p>Vous n'avez aucun billet à revendre.</p>
    <?php endif; ?>

    <a href="profil.php">Retour au profil</a>
</body>
</html>
