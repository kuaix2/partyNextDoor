<?php

$servername = "localhost"; // Hôte de la base de données
$username = "root"; // Nom d'utilisateur de la base de données
$password = ""; // Mot de passe de la base de données
$dbname = "bddpartynextdoor"; // Nom de la base de données

// Clé secrète reCAPTCHA
$recaptcha_secret = "6LfQJ7YqAAAAAJcoJKEMFV9T4hkARujnw8_pjUE4";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si les données du formulaire ont été soumises
if (isset($_POST['nom_utilisateur'], $_POST['email'], $_POST['mot_de_passe'], $_POST['g-recaptcha-response'])) {
    // Récupérer et sécuriser les entrées utilisateur
    $nom_utilisateur = $conn->real_escape_string($_POST['nom_utilisateur']);
    $email = $conn->real_escape_string($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe']; // Ne pas sécuriser le mot de passe, il sera haché

    // Valider le reCAPTCHA
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify_url = "https://www.google.com/recaptcha/api/siteverify";
    $verify_data = [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    ];

    // Requête à l'API reCAPTCHA
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($verify_data)
        ]
    ];
    $context = stream_context_create($options);
    $verify_result = file_get_contents($verify_url, false, $context);
    $verify_result = json_decode($verify_result);

    if (!$verify_result->success) {
        echo "Échec de la vérification reCAPTCHA. Veuillez réessayer.";
        exit;
    }

    // Valider le format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    // Hacher le mot de passe avant de le stocker
    $mot_de_passe_hashed = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Insérer les données dans la base de données
    $sql = "INSERT INTO Utilisateur (nom_utilisateur, email, mot_de_passe) 
            VALUES ('$nom_utilisateur', '$email', '$mot_de_passe_hashed')";

    if ($conn->query($sql) === TRUE) {
        echo "Votre nom est " . htmlspecialchars($nom_utilisateur) . ", et il a été ajouté à la base de données.";
    } else {
        echo "Erreur : " . $conn->error;
    }
} else {
    echo "Tous les champs sont requis.";
}

// Fermer la connexion
$conn->close();

?>

<!-- Bouton pour retourner à accueil.html -->
<form action="../accueil_hors_connexion.php">
    <button type="submit">Retour à la page d'accueil</button>
</form>
