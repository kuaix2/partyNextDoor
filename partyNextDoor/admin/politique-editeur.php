<?php
session_start(); // Démarre la session

// Vérifier si l'utilisateur est connecté et rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// L'utilisateur est connecté, donc on continue d'afficher la page
?>

<?php
include '../database/db_conn.php';

$contents = [];

// Gérer la soumission du formulaire pour mettre à jour le contenu de plusieurs pages
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $page_id => $updated_content) {
        $sql = "UPDATE multiple_content SET content = ? WHERE page_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $updated_content, $page_id);

        if (!$stmt->execute()) {
            die("Erreur lors de la mise à jour du contenu de la page $page_id: " . $stmt->error);
        }

        $stmt->close();
    }
    $message = "Contenu mis à jour avec succès !";
}

// Récupérer le contenu existant pour toutes les pages
$sql = "SELECT page_id, page_name, content FROM multiple_content";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contents[$row['page_id']] = [
            'name' => $row['page_name'],
            'content' => $row['content']
        ];
    }
} else {
    die("Aucune page trouvée dans la base de données.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Plusieurs Pages</title>
    <script src="https://cdn.tiny.cloud/1/pn2zh1v0rk8aaco8a825efyux308vo1pt0rmkw3f9i1g5z0i/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({
                selector: '.editor',
                plugins: 'advlist autolink lists link image charmap print preview anchor help',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | help',
                height: 300,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        });
    </script>
</head>
<body>

    <h1>Modifier Plusieurs Pages</h1>

    <!-- Afficher le message de succès si le contenu a été mis à jour -->
    <?php if (isset($message)): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Formulaire pour modifier plusieurs pages -->
    <form action="" method="POST">
        <?php foreach ($contents as $page_id => $data): ?>
            <h2><?php echo htmlspecialchars($data['name']); ?></h2>
            <textarea id="editor-<?php echo $page_id; ?>" class="editor" name="<?php echo $page_id; ?>">
                <?php echo htmlspecialchars($data['content']); ?>
            </textarea>
        <?php endforeach; ?>
        <button type="submit">Enregistrer toutes les modifications</button>
    </form>

    <form action="" method="POST">
        <button type="button" onclick="window.location.href='admin_page.php';">Retour à la page d'administration</button>
    </form>

</body>
</html>
