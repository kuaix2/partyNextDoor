<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "bddpartynextdoor";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Traitement de l'enregistrement d'une question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $question = htmlspecialchars($_POST['question']); // Sécurisation des données

    // Vérifier que les champs ne sont pas vides
    if (!empty($email) && !empty($question)) {
        $stmt = $conn->prepare("INSERT INTO faq (user_email, message, created_at) VALUES (?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("ss", $email, $question);

            if ($stmt->execute()) {
                echo "succès"; // Réponse claire pour JavaScript
            } else {
                echo "Erreur lors de l'enregistrement : " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur dans la préparation de la requête : " . $conn->error;
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>
