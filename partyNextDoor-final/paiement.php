<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : (isset($_GET['event_id']) ? intval($_GET['event_id']) : 0);

if ($event_id > 0) {
    
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        die("Événement introuvable.");
    }

    $stmt->close();
} else {
    die("Aucun ID d'événement fourni.");
}


session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

// Traitement du paiement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_payment'])) {
    $conn->begin_transaction();

    try {
        
        $stmt = $conn->prepare("SELECT places_available FROM events WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if ($row['places_available'] > 0) {
                $new_places = $row['places_available'] - 1;

                
                $stmt = $conn->prepare("UPDATE events SET places_available = ? WHERE id = ?");
                $stmt->bind_param("ii", $new_places, $event_id);
                $stmt->execute();

               
                $stmt = $conn->prepare("INSERT INTO tickets (user_id, event_id, price) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $user_id, $event_id, $event['event_price']);
                $stmt->execute();

                $ticket_id = $conn->insert_id;

                
                $conn->commit();

                
                header("Location: confirmation-paiement.php?ticket_id=$ticket_id");
                exit();
            } else {
                throw new Exception("Désolé, il n'y a plus de places disponibles pour cet événement.");
            }
        } else {
            throw new Exception("Événement introuvable.");
        }
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
    <link rel="stylesheet" href="css/paiement.css">
    <title>Paiement</title>
</head>
<body>
    <div class="main-container">
        <div class="payment-info">
    <h1>Paiement pour l'événement : <?php echo htmlspecialchars($event['event_name']); ?></h1>
    <p>Date : <?php echo htmlspecialchars($event['event_date']); ?></p>
    <p>Prix : <?php echo number_format($event['event_price'], 2, ',', ''); ?> €</p>
    <p>Places disponibles : <?php echo htmlspecialchars($event['places_available']); ?></p>
    </div>
    <form action="paiement.php" method="post">
    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
    <div class="payment-fields-container">
<div class="form-group">
        <label>Numéro de Carte</label>
        <input type="text" name="numero_carte"  placeholder="Numéro de carte"required>
    </div>

    <div class="form-group">
        <label>Date de fin de validité (MM/AA)</label>
        <input type="text" name="date_fin_validite"  placeholder="MM/AA"required>
    </div>

    <div class="form-group">
        <label>Cryptogramme visuel</label>
        <input type="text" name="cryptogramme_visuel"  placeholder="Cryptographie(CVV/CVC)"required>
    </div>
    </div>


    
</form>

    <form action="paiement.php" method="post">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
        <button type="submit" name="confirm_payment">Valider le paiement</button>
        <a href="fiche-evenement.php?id=<?php echo $event_id; ?>">Annuler</a>
    </form>
    </div>
</body>
</html>
