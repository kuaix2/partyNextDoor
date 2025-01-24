<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header("Location: admin_login.php");
            exit();
        }
    }
    
    public function handleDelete($get_params) {
        if (isset($get_params['delete'])) {
            return $this->model->deleteUser($get_params['delete']);
        }
        return false;
    }
    
    public function getAllUsers() {
        return $this->model->getAllUsers();
    }
    
    public function sendWarningEmail($email) {
        $user = $this->model->getUserByEmail($email);
        if (!$user) {
            return "Adresse mail incorrecte.";
        }
        
        try {
            $mail_config = require 'config/mail.php';
            $mail = new PHPMailer(true);
            
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = $mail_config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mail_config['smtp_username'];
            $mail->Password = $mail_config['smtp_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mail_config['smtp_port'];
            
            $mail->setFrom($mail_config['smtp_username'], $mail_config['from_name']);
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Avertissement : Inactivité sur votre compte';
            $mail->Body = "Bonjour,<br><br>Nous vous informons que cela fait longtemps que vous avez utiliser votre compte Partynextdoor. Ce message est un avertissement. Si vous ne vous reconnectez pas, votre compte sera supprimé.<br><br>Cordialement,<br>Équipe PartyNextDoor.";
            
            $mail->send();
            return "Avertissement envoyé à l'utilisateur.";
        } catch (Exception $e) {
            return "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    }
    
    public function handleWarningEmail($get_params) {
        if (isset($get_params['warning'])) {
            return $this->sendWarningEmail($get_params['warning']);
        }
        return false;
    }
} 