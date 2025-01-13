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
$sql = "SELECT id, email, warning_count FROM utilisateur WHERE TIMESTAMPDIFF(SECOND, last_activity, NOW()) > ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inactive_period);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Supprimer les utilisateurs inactifs après 3 avertissements
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $user_email = $row['email'];
        $warning_count = $row['warning_count'];

        // Vérifier si l'utilisateur a déjà reçu 3 avertissements
        if ($warning_count < 3) {
            // Envoyer un avertissement par email
            $subject = "Avertissement : Inactivité sur votre compte";
            $message = "Bonjour,\n\nVous n'avez pas utilisé votre compte depuis plus de 5 minutes. Ce message est un avertissement. Si vous ne vous reconnectez pas dans les 5 prochaines minutes, votre compte sera supprimé.\n\nCordialement,\nL'équipe PartyNextDoor.";
            $headers = "From: no-reply@partynextdoor.com\r\n";
            $headers .= "Reply-To: support@partynextdoor.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (mail($user_email, $subject, $message, $headers)) {
                echo "Avertissement envoyé à l'utilisateur avec ID " . $user_id . ".<br>";

                // Mettre à jour le compteur d'avertissements
                $update_warning_sql = "UPDATE utilisateur SET warning_count = warning_count + 1 WHERE id = ?";
                $update_warning_stmt = $conn->prepare($update_warning_sql);
                $update_warning_stmt->bind_param("i", $user_id);
                $update_warning_stmt->execute();
                $update_warning_stmt->close();
            } else {
                echo "Erreur lors de l'envoi de l'avertissement à l'utilisateur avec ID " . $user_id . ".<br>";
            }
        } else {
            // Si l'utilisateur a reçu 3 avertissements, supprimer son compte
            $delete_sql = "DELETE FROM utilisateur WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $user_id);
            
            if ($delete_stmt->execute()) {
                // Vérifier si la suppression a réussi
                if ($delete_stmt->affected_rows > 0) {
                    echo "Utilisateur avec ID " . $user_id . " supprimé avec succès après 3 avertissements.<br>";

                    // Envoi de l'email de notification de suppression
                    $subject = "Notification : Suppression de votre compte";
                    $message = "Bonjour,\n\nVotre compte a été supprimé après avoir reçu 3 avertissements pour inactivité. Si vous avez des questions, n'hésitez pas à nous contacter.\n\nCordialement,\nL'équipe PartyNextDoor.";
                    $headers = "From: no-reply@partynextdoor.com\r\n";
                    $headers .= "Reply-To: support@partynextdoor.com\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    if (mail($user_email, $subject, $message, $headers)) {
                        echo "Email de notification envoyé pour la suppression du compte de l'utilisateur avec ID " . $user_id . ".<br>";
                    } else {
                        echo "Erreur lors de l'envoi de l'email de notification pour la suppression du compte de l'utilisateur avec ID " . $user_id . ".<br>";
                    }
                } else {
                    echo "Aucune ligne supprimée pour l'utilisateur avec ID " . $user_id . ".<br>";
                }
            } else {
                echo "Erreur lors de la suppression de l'utilisateur avec ID " . $user_id . ".<br>";
            }
        }
        
        $delete_stmt->close();
    }
} else {
    echo "Aucun utilisateur inactif trouvé.<br>";
}

$stmt->close();
$conn->close();
?>
