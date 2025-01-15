<?php
// Démarrer la session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifiez si un ID de ticket est fourni dans l'URL
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;

if ($ticket_id <= 0) {
    die("Aucun ticket valide n'a été spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/confirmation-paiment-revente.css">
    <title>Confirmation de Paiement</title>
        
</head>
<body>
    <div class="confirmation-container">
        <h1>Paiement confirmé !</h1>
        <p>Votre paiement pour le billet ID <?php echo htmlspecialchars($ticket_id); ?> a été validé avec succès.</p>
        <p>Vous recevrez un email avec tous les détails de votre achat.</p>
        <a href="billets-revente.php">Retourner à la page des billets</a>
    </div>
</body>
</html>
