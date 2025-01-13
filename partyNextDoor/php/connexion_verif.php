<?php
session_start(); // Démarre la session ici, avant tout code

// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$message = '';

// Vérification des champs du formulaire
if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête SQL pour éviter les injections
    $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérification du mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            // Stocker l'état de connexion dans la session
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; // Stocke l'ID de l'utilisateur dans la session

            // Redirection vers la page d'accueil après connexion réussie
            header("Location: ../accueil.php");
            exit; // N'oublie pas d'ajouter exit() pour arrêter l'exécution du script après la redirection
        } else {
            $message = "Mot de passe ou nom incorrect.";
        }
    } else {
        $message = "Mot de passe ou nom incorrect.";
    }

    $stmt->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "Veuillez remplir tous les champs.";
}

if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();

    // Supprimer le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    // Redirection après déconnexion
    header("Location: ../connexion.php?logout=success");
    exit();
}

// Vérifier le statut de connexion
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_page.php");
    exit();
}

// Si un message d'erreur est défini, le passer dans l'URL
if ($message) {
    header("Location: ../connexion.php?error=" . urlencode($message));
    exit;
}
?>
