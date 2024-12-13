<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (isset($_POST['username'], $_POST['password'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; 

    $sql = "SELECT * FROM Utilisateur WHERE nom_utilisateur = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($user['nom_utilisateur']) . "!";
        } else {
            echo "Erreur : Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        // Utilisateur non trouvé
        echo "Erreur : Nom d'utilisateur ou mot de passe incorrect.";
    }
} else {
    echo "Veuillez remplir tous les champs.";
}

// Fermer la connexion
$conn->close();

?>
