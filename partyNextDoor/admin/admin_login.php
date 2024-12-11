<?php
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $conn->real_escape_string($_POST['username']);
    $adminPassword = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$adminUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Vérifier le mot de passe (fixe)
        if ($adminPassword == "admin123") { 
            $_SESSION['admin_logged_in'] = true; 
            $_SESSION['admin_username'] = $adminUsername; 
            
            // Rediriger vers la page de toutes les fonctionnalités des admins
            header("Location: admin_page.php");
            exit();
        } else {
            $message = "Nom d'utilisateur ou mot de passe invalide !";
        }
    } else {
        $message = "Nom d'utilisateur ou mot de passe invalide !";
    }
}

// Redirige vers la page de connexion et supprime les données de la session
if (isset($_GET['logout'])) {
    session_unset();    
    session_destroy();  
    header("Location: admin_login.php"); 
    exit();
}

// Vérifier le statut de connexion
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_page.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #45a049;
        }
        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-form">
        <h2>Connexion Admin</h2>

        <!--Affiche le message de echou de connexion-->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="POST" action="admin_login.php">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>

</body>
</html>
