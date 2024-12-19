<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$message = '';

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête SQL avec un paramètre pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $username); // "s" indique que le paramètre est une chaîne

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Si les identifiants sont corrects, rediriger 
            header("Location: ../accueil.php");
            exit;
        } else {
            // Si le mot de passe est incorrect, définir le message d'erreur
            $message = "Mot de passe ou nom incorrect.";
        }
    } else {
        // Si l'utilisateur n'existe pas, définir le message d'erreur
        $message = "Mot de passe ou nom incorrect.";
    }

    $stmt->close();
} else {
    $message = "Veuillez remplir tous les champs.";
}

// Fermer la connexion
$conn->close();

// Passer le message d'erreur en paramètre de l'URL
if ($message) {
    header("Location: ../connexion.php?error=" . urlencode($message));
    exit;
}
?>
