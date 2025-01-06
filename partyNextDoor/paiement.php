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

// Récupérer l'ID de l'événement depuis l'URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if ($event_id > 0) {
    // Vérifier que l'événement existe
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Événement introuvable.");
    }

    $stmt->close();
} else {
    die("Aucun ID d'événement fourni.");
}

// Traitement du formulaire de paiement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_carte = $_POST['numero_carte'];
    $date_fin_validite = $_POST['date_fin_validite'];
    $cryptogramme_visuel = $_POST['cryptogramme_visuel'];

    // Associer le paiement à l'événement
    $sql = "INSERT INTO paiements (event_id, numero_carte, date_fin_validite, cryptogramme_visuel) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $event_id, $numero_carte, $date_fin_validite, $cryptogramme_visuel);

    if ($stmt->execute()) {
        echo "Paiement enregistré avec succès.";
        // Redirection vers une page de confirmation
        header("Location: success.php?event_id=$event_id");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement du paiement: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE HTML>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/page-event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">  
    <link rel="stylesheet" href="css/header.css">
    <title>Paiement</title>
</head>
<body>
    <div class="picture">
        <img src="images/logo.png" alt="logo">
    </div>
    <form action="paiement.php" method="post">

        <div class="form-group">
            <label>Numéro de Carte</label>
            <input type="number" name="numero_carte" required>
        </div>

        <div class="form-group">
            <label>Date de fin de validité (MM/AA)</label>
            <input type="number" name="date_fin_validite" required>
        </div>

        <div class="form-group">
            <label>Cryptogramme visuel</label>
            <input type="number" name="cryptogramme_visuel" required>
        </div>

        <button type="submit">Valider</button>
        <a href="fiche-evenement.php" class="btn">Annuler</a>
    </form>
</body>
</html>