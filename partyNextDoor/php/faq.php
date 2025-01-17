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
    $question = htmlspecialchars($_POST['question']); // Utiliser htmlspecialchars pour éviter FILTER_SANITIZE_STRING

    // Vérifier que les champs ne sont pas vides
    if (!empty($email) && !empty($question)) {
        $stmt = $conn->prepare("INSERT INTO faq (user_email, message, created_at) VALUES (?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("ss", $email, $question);

            if ($stmt->execute()) {
                $responseMessage = "Votre question a été enregistrée avec succès. Merci !";
            } else {
                $responseMessage = "Erreur lors de l'enregistrement de votre question.";
            }

            $stmt->close();
        } else {
            $responseMessage = "Erreur dans la préparation de la requête : " . $conn->error;
        }
    } else {
        $responseMessage = "Veuillez remplir tous les champs.";
    }
}

// Récupération de toutes les questions pour les afficher
$questions = [];
$query = "SELECT message, created_at FROM faq ORDER BY created_at DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

$conn->close();
?>