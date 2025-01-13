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

// Vérifiez si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

// Récupérer l'ID de la revente
$revente_id = isset($_GET['revente_id']) ? intval($_GET['revente_id']) : 0;

if ($revente_id > 0) {
    // Vérifier que le billet est disponible à la revente
    $sql = "
        SELECT rt.ticket_id, rt.price, e.event_name, e.event_date
        FROM revente_tickets rt
        JOIN tickets t ON rt.ticket_id = t.id
        JOIN events e ON t.event_id = e.id
        WHERE rt.id = ? AND rt.status = 'en_vente'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $revente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $billet = $result->fetch_assoc();
    } else {
        die("Billet non disponible.");
    }
    $stmt->close();
} else {
    die("Aucun billet sélectionné.");
}

// Traitement de la confirmation d'achat
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_payment'])) {
    $conn->begin_transaction();

    try {
        // Mettre à jour l'utilisateur associé au billet
        $stmt = $conn->prepare("UPDATE tickets SET user_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $user_id, $billet['ticket_id']);
        $stmt->execute();

        // Supprimer l'entrée de revente
        $stmt = $conn->prepare("DELETE FROM revente_tickets WHERE id = ?");
        $stmt->bind_param("i", $revente_id);
        $stmt->execute();

        // Valider la transaction
        $conn->commit();

        echo "<script>alert('Achat réussi !');</script>";
        header("Location: confirmation-paiement.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Erreur : " . $e->getMessage());
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
</head>
<body>
    <h1>Paiement pour l'événement : <?php echo htmlspecialchars($billet['event_name']); ?></h1>
    <p>Date : <?php echo htmlspecialchars($billet['event_date']); ?></p>
    <p>Prix : <?php echo number_format($billet['price'], 2, ',', ''); ?> €</p>

    <form action="paiement.php?revente_id=<?php echo $revente_id; ?>" method="post">
        <button type="submit" name="confirm_payment">Confirmer l'achat</button>
        <a href="billets-revente.php">Annuler</a>
    </form>
</body>
</html>
