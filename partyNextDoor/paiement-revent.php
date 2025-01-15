<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Démarrer une session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // ID utilisateur connecté

// Récupérer l'ID de la revente depuis l'URL
$revente_id = isset($_GET['revente_id']) ? intval($_GET['revente_id']) : 0;

// Vérifier si l'ID de la revente est valide et récupérer les données
if ($revente_id > 0) {
    $sql = "
        SELECT rt.id AS revente_id, rt.price AS revente_price, t.id AS ticket_id, e.event_name, e.event_date, e.event_image
        FROM revente_tickets rt
        JOIN tickets t ON rt.ticket_id = t.id
        JOIN events e ON t.event_id = e.id
        WHERE rt.id = ? AND rt.status = 'en_vente'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $revente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $revente = $result->fetch_assoc();
    } else {
        die("Billet non disponible ou introuvable.");
    }
    $stmt->close();
} else {
    die("Aucun ID de revente fourni.");
}

// Traitement du paiement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_payment'])) {
    // Démarrer une transaction SQL
    $conn->begin_transaction();

    try {
        // Mettre à jour le propriétaire du billet
        $stmt = $conn->prepare("UPDATE tickets SET user_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $user_id, $revente['ticket_id']);
        $stmt->execute();

        // Supprimer l'entrée de revente
        $stmt = $conn->prepare("DELETE FROM revente_tickets WHERE id = ?");
        $stmt->bind_param("i", $revente_id);
        $stmt->execute();

        // Valider la transaction
        $conn->commit();

        // Redirection vers une page de confirmation
        header("Location: ./confirmation-paiment-revente.php?ticket_id=" . $revente['ticket_id']);
        exit();
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollback();
        die("Erreur lors du traitement du paiement : " . $e->getMessage());
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/paiement-revent.css">
    <title>Paiement Revente</title>
    
</head>
<body>
    <div class="main-container">
        <div class="payment-info">
            <h1>Achat du billet pour : <?php echo htmlspecialchars($revente['event_name']); ?></h1>
            <p>Date de l'événement : <?php echo htmlspecialchars($revente['event_date']); ?></p>
            <img src="<?php echo htmlspecialchars($revente['event_image']); ?>" alt="Image de l'événement" style="width: 100%; margin-top: 15px;">
            <p>Prix : <?php echo number_format($revente['revente_price'], 2, ',', ''); ?> €</p>
        </div>

        <form action="paiement-revent.php?revente_id=<?php echo $revente_id; ?>" method="post">
            <div class="form-group">
                <label>Numéro de Carte</label>
                <input type="text" name="numero_carte" pattern="\d{16}" maxlength="19" placeholder="1234 5678 9123 4567" required>
            </div>

            <div class="form-group">
                <label>Date de fin de validité (MM/AA)</label>
                <input type="text" name="date_fin_validite" pattern="\d{2}\/\d{2}" placeholder="MM/AA" required>
            </div>

            <div class="form-group">
                <label>Cryptogramme visuel</label>
                <input type="text" name="cryptogramme_visuel" pattern="\d{3}" maxlength="3" placeholder="123" required>
            </div>

            <button type="submit" name="confirm_payment">Valider le paiement</button>
            <a href="billets-revente.php">Annuler</a>
        </form>
    </div>
</body>
</html>
