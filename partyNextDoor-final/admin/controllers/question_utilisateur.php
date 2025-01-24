<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions des Utilisateurs</title>
    <link rel="stylesheet" href="../assets/css/question_utilisateur.css">
    </head>
<body>
    <div class="container">
        <h1>Questions des Utilisateurs</h1>
        <table>
            <thead>
                <tr>
                    <th>Adresse e-mail</th>
                    <th>Question</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Simulation de données (à remplacer par votre base de données)
                $questions = [
                    // Exemple d'entrée :
                    // ['email' => 'user@example.com', 'question' => 'Comment réinitialiser mon mot de passe ?']
                ];

                // Vérifier si des données sont disponibles
                if (!empty($questions)) {
                    foreach ($questions as $item) {
                        echo '<tr>';
                        echo '<td>';
                        echo '<span class="email">' . htmlspecialchars($item['email']) . '</span>';
                        echo '<button class="copy-btn" onclick="copyToClipboard(\'' . htmlspecialchars($item['email']) . '\')">Copier</button>';
                        echo '</td>';
                        echo '<td>' . htmlspecialchars($item['question']) . '</td>';
                        echo '<td><button class="action-btn">Répondre</button></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3" style="text-align:center;">Aucune question disponible</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function copyToClipboard(email) {
            navigator.clipboard.writeText(email).then(() => {
                alert("Adresse email copiée : " + email);
            }).catch(err => {
                console.error("Erreur lors de la copie :", err);
            });
        }
    </script>
</body>
</html>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f4f4f4;
    color: #333;
}

.copy-btn, .action-btn {
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.copy-btn {
    background-color: #007BFF;
    color: #fff;
}

.copy-btn:hover {
    background-color: #0056b3;
}

.action-btn {
    background-color: #28a745;
    color: #fff;
}

.action-btn:hover {
    background-color: #218838;
}

.email {
    margin-right: 10px;
    font-weight: bold;
    color: #333;
}
</style>