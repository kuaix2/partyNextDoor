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

// Définir la période d'inactivité en secondes (1 minute)
$inactive_period = 60; // 1 minute en secondes

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