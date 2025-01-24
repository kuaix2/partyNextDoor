<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/connexion.css">
</head>

<body>
    <form action="php/connexion_verif.php" method="POST">
        <div class="form-container">
            <h1><strong>Se connecter</strong></h1>

            
            <?php
            if (isset($_GET['error'])) {
                echo '<p style="color: red; text-align: center;">' . htmlspecialchars($_GET['error']) . '</p>';
            }
            ?>

            
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required placeholder="Entrez votre nom d'utilisateur">
            </div>

            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
            </div>

           
            <div class="forgot-password">
                <a href="mdp_oublie/mdp1.php">Mot de passe oublié ?</a>
            </div>

           
            <button type="submit" class="submit-button">Se connecter</button>
        </div>
    </form>
</body>

</html>
