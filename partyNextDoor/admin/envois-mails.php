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
                // Envoi de l'email d'avertissement
                $mail = new PHPMailer(true);

                try {
                    // Paramètres SMTP
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'votre_email@gmail.com';
                    $mail->Password   = 'votre_mot_de_passe'; // Remplacez avec votre mot de passe
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('votre_email@gmail.com', 'PartyNextDoor Support');
                    $mail->addAddress($email);

                    // Contenu de l'email
                    $mail->isHTML(true);
                    $mail->Subject = 'Avertissement : Inactivité sur votre compte';
                    $mail->Body    = "Bonjour,<br><br>Nous vous informons que cela fait longtemps que vous avez utiliser votre compte Partynextdoor.Ce message est un avertissement. Si vous ne vous reconnectez pas , votre compte sera supprimé.<br><br>Cordialement,<br>Équipe PartyNextDoor.";

                    $mail->send();
                    $message = "Avertissement envoyé à l'utilisateur.";
                } catch (Exception $e) {
                    $message = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
                }
            } else {
                $message = "Adresse mail incorrecte.";
            }
        }

        $conn->close();
        ?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avertissement avant suppression du compte</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .message-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .message-container h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #333;
        }

        .message-container p {
            font-size: 1em;
            margin-bottom: 20px;
            color: #555;
        }

        .message-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .message-container button {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            color: #fff;
            background-color: #333;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message-container button:hover {
            background-color: #555;
        }

        .message {
            margin-top: 20px;
            font-size: 1em;
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h1>Avertissement de suppression</h1>
        <p>Un e-mail d'avertissement sera envoyé pour prévenir la suppression de votre compte en cas d'inactivité.</p>
        <form method="POST">
            <input type="email" id="email" name="email" placeholder="Entrez l'adresse e-mail" required>
            <button type="submit">Envoyer l'avertissement</button>
        </form>

        <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>
