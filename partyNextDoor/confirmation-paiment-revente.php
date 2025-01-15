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
    <title>Confirmation de Paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .confirmation-container {
            max-width: 500px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .confirmation-container h1 {
            color: #28a745;
            font-size: 24px;
        }
        .confirmation-container p {
            font-size: 16px;
            margin: 10px 0 20px;
        }
        .confirmation-container a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .confirmation-container a:hover {
            background-color: #0056b3;
        }
    </style>
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
