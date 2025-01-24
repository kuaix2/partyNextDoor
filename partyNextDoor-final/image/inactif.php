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

// Définir la période d'inactivité (3 mois)
$inactive_period = 60; // 3 mois en secondes

// Obtenir la date actuelle
$current_time = time();

// Requête SQL pour sélectionner les utilisateurs inactifs
$sql = "SELECT id, last_activity FROM utilisateur WHERE ($current_time - UNIX_TIMESTAMP(last_activity)) > $inactive_period";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Supprimer les utilisateurs inactifs
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $delete_sql = "DELETE FROM utilisateur WHERE id = $user_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "Utilisateur avec ID $user_id supprimé avec succès.<br>";
        } else {
            echo "Erreur lors de la suppression de l'utilisateur avec ID $user_id: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Aucun utilisateur inactif trouvé.";
}

$conn->close();
?>
