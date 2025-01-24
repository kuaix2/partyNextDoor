<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $question = htmlspecialchars($_POST['question']);

   
    if (!empty($email) && !empty($question)) {
        $stmt = $conn->prepare("INSERT INTO faq (user_email, message, created_at) VALUES (?, ?, NOW())");

        if ($stmt) {
            $stmt->bind_param("ss", $email, $question);

            if ($stmt->execute()) {
                echo "succès"; 
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
