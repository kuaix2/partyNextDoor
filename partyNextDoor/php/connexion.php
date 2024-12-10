<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si les données du formulaire ont été envoyées
if (isset($_POST['username'], $_POST['password'])) {
    // Récupérer et échapper les données utilisateur
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Ne pas échapper ici pour comparer au hash plus tard

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT * FROM Utilisateur WHERE nom_utilisateur = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les données de l'utilisateur
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Mot de passe correct, connecter l'utilisateur
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($user['nom_utilisateur']) . "!";
        } else {
            // Mot de passe incorrect
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
