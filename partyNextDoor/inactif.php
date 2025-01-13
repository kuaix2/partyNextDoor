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

// Mettre à jour l'horodatage de l'utilisateur (ex: lors de la connexion ou de l'action)
$user_id = 1; // ID de l'utilisateur (à remplacer par l'ID réel de l'utilisateur)
$update_sql = "UPDATE utilisateur SET last_activity = NOW() WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $user_id);
$update_stmt->execute();

// Définir la période d'inactivité en secondes (5 minutes)
$inactive_period = 300; // 5 minutes en secondes

// Requête SQL pour sélectionner les utilisateurs inactifs
$sql = "SELECT id FROM utilisateur WHERE TIMESTAMPDIFF(SECOND, last_activity, NOW()) > ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inactive_period);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Supprimer les utilisateurs inactifs
    while ($row = $result->fetch_assoc()) {
        $delete_sql = "DELETE FROM utilisateur WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $row['id']);
        $delete_stmt->execute();
        
        // Vérifier si la suppression a réussi
        if ($delete_stmt->affected_rows > 0) {
            echo "Utilisateur avec ID " . $row['id'] . " supprimé avec succès.<br>";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur avec ID " . $row['id'] . ".<br>";
        }
        
        $delete_stmt->close();
    }
} else {
    echo "Aucun utilisateur inactif trouvé.<br>";
}

$stmt->close();
$conn->close();
?>
