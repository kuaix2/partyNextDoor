<?php 
// Connexion à la base de données
$host = 'localhost';
$dbname = 'bddpartynextdoor';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Démarrage de la session
session_start();
$userId = $_SESSION['user_id'] ?? 1; // Remplacez par la gestion de session appropriée

// Récupérer les informations actuelles de l'utilisateur
$sql = "SELECT nom_utilisateur, email, mot_de_passe, nom_de_famille, prenom, bio, photo_profil FROM utilisateur WHERE id = :userId";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur non trouvé.");
}

// Mettre à jour les informations si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gestion de l'upload de la photo
    $photoProfil = $user['photo_profil']; // Chemin actuel

    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['photo_profil']['name']);
        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadFile)) {
            $photoProfil = $uploadFile;
        } else {
            echo "Erreur lors de l'upload de la photo.";
        }
    }

    // Récupérer et mettre à jour les autres informations
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $nom_de_famille = $_POST['nom_de_famille'];
    $prenom = $_POST['prenom'];
    $bio = $_POST['bio'];

    $sqlUpdate = "UPDATE utilisateur SET nom_utilisateur = :nom_utilisateur, email = :email, mot_de_passe = :mot_de_passe, nom_de_famille = :nom_de_famille, prenom = :prenom, bio = :bio, photo_profil = :photo_profil WHERE id = :userId";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':nom_utilisateur', $nom_utilisateur, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':nom_de_famille', $nom_de_famille, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':bio', $bio, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':photo_profil', $photoProfil, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmtUpdate->execute()) {
        header("Location: profil.php"); // Redirige vers la page de profil après mise à jour
        exit();
    } else {
        echo "Erreur lors de la mise à jour des informations.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/modif_profil.css">
</head>
<body>
    <div class="profile-container">
        <h1>Modifier le Profil</h1>
        <div class="profile-section">
            <!-- Photo de profil -->
            <form action="" method="POST" class="profile-form" enctype="multipart/form-data">
                <div class="profile-picture">
                    <img src="<?= htmlspecialchars($user['photo_profil'] ?: 'image/default.jpg') ?>" alt="Photo de profil" id="profile-img">
                    <input type="file" name="photo_profil" id="change-photo" accept="image/*">
                </div>

                <!-- Informations utilisateur -->
                <label for="nom_utilisateur">Nom d'utilisateur</label>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" value="<?= htmlspecialchars($user['nom_utilisateur']) ?>" required>

                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" value="<?= htmlspecialchars($user['mot_de_passe']) ?>" required>

                <label for="nom_de_famille">Nom de famille</label>
                <input type="text" id="nom_de_famille" name="nom_de_famille" value="<?= htmlspecialchars($user['nom_de_famille']) ?>" required>

                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>

                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

                <button type="submit">Enregistrer les modifications</button>
            </form>
        </div>

        <!-- Rubriques supplémentaires -->
        
    </div>
</body>
</html>
