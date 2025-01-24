<?php
session_start();

// Load database configuration
$config = require_once 'config/database.php';

// Database connection
$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname']
);

if ($conn->connect_error) {
    die("\u00c9chec de la connexion : " . $conn->connect_error);
}


// Récupérer toutes les questions de la table `faq`
$query = "SELECT id, user_email, message, created_at FROM faq ORDER BY created_at DESC";
$result = $conn->query($query);

// Fermer la connexion à la base de données après récupération des données
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponses FAQ - Administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        header p {
            margin: 5px 0 0;
            font-size: 1.2em;
        }

        header a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #fff;
            color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
        }

        header a:hover {
            background-color: #f0f0f0;
        }

        .faq-table {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .faq-table h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td {
            word-wrap: break-word;
        }

        .copy-icon {
            margin-left: 10px;
            cursor: pointer;
            color: #4CAF50;
            font-size: 1.2em;
        }

        .copy-icon:hover {
            color: #2e7d32;
        }

        p {
            text-align: center;
            font-size: 1.2em;
            color: #333;
        }
    </style>
    <script>
        function copyToClipboard(email) {
            navigator.clipboard.writeText(email).then(() => {
                alert(`L'email \"${email}\" a été copié dans le presse-papier.`);
            }).catch(err => {
                console.error('Erreur lors de la copie : ', err);
            });
        }
    </script>
</head>
<body>
    <header>
        <h1>Administration - Questions FAQ</h1>
        <p>Consultez toutes les questions posées par les utilisateurs.</p>
        <a href="admin_page.php">Retour au tableau de bord</a>
    </header>

    <section class="faq-table">
        <h2>Liste des Questions</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email de l'utilisateur</th>
                        <th>Question</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($row['user_email']); ?>
                                <span class="copy-icon" onclick="copyToClipboard('<?php echo htmlspecialchars($row['user_email']); ?>')">&#x1F4CB;</span>
                            </td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune question n'a été trouvée dans la base de données.</p>
        <?php endif; ?>
    </section>
</body>
</html>
