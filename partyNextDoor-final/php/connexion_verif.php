<?php
session_start(); 


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

    
    $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['mot_de_passe'])) {
            
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; 

            
            header("Location: ../accueil.php");
            exit; 
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

    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    
    header("Location: ../connexion.php?logout=success");
    exit();
}



if ($message) {
    header("Location: ../connexion.php?error=" . urlencode($message));
    exit;
}
?>
