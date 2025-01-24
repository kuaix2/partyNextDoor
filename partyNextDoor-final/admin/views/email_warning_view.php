<?php
class EmailWarningView {
    public function render($message = '') {
        ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avertissement avant suppression du compte</title>
    <link rel="stylesheet" href="assets/css/email_warning.css">
</head>
<body>
    <div class="message-container">
        <h1>Avertissement de suppression</h1>
        <p>Un e-mail d'avertissement sera envoyé pour prévenir la suppression de votre compte en cas d'inactivité.</p>
        <form method="POST">
            <input type="email" id="email" name="email" placeholder="Entrez l'adresse e-mail" required>
            <button type="submit">Envoyer l'avertissement</button>
        </form>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <div class="button-group">
            <button type="button" class="back-btn" onclick="window.location.href='admin_page.php';">
                Retour à la page d'administration
            </button>
        </div>
    </div>
</body>
</html>
<?php
        return ob_get_clean();
    }
} 