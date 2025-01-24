<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de Passe Oublié</title>
    <link rel="stylesheet" href="style/mbp1.css">
    
</head>
<body>
    <div class="forgot-password-container">
        <h1>Mot de Passe Oublié</h1>
        <p>Veuillez entrer votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
        <?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require '../vendor/autoload.php';


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bddpartynextdoor";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        $message = "";

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
            $email = $conn->real_escape_string($_POST['email']);
            $sql = "SELECT * FROM Utilisateur WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $token = bin2hex(random_bytes(50));
                $expire_time = date("Y-m-d H:i:s", strtotime('+24 hour'));
                $sql = "UPDATE Utilisateur SET token='$token', token_expiration='$expire_time' WHERE email='$email'";
                $conn->query($sql);

                // Vérifie le port MAMP
                $port = "80"; // Change si nécessaire
                $reset_link = "http://localhost:$port/partyNextDoor/partyNextDoor/mdp_oublie/reinitialisation_mdp.php?token=$token";

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = '6newdev@gmail.com';
                    $mail->Password   = 'dxuv xowi iogg owmf';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('6newdev@gmail.com', 'PartyNextDoor Support');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Réinitialisation de votre mot de passe';
                    $mail->Body    = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$reset_link'>$reset_link</a>";

                    $mail->send();
                    $message = "Lien de réinitialisation envoyé.";
                } catch (Exception $e) {
                    $message = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
                }
            } else {
                $message = "Adresse mail incorrecte.";
            }
        }

        $conn->close();
        ?>
        <form method="POST">
            <input type="email" id="email" name="email" placeholder="Votre adresse e-mail" required>
            <button type="submit">Entrer</button>
        </form>
        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>
