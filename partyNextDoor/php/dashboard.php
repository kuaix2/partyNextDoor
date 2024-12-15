<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Gérer l'envoi du formulaire pour ajouter l'événement à la base de données
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_name'])) {
    // Nettoyer les entrées utilisateur
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $eventAdress = $conn->real_escape_string($_POST['event_adress']);
    $eventDate = $conn->real_escape_string($_POST['event_date']);
    $eventPrice = $conn->real_escape_string($_POST['event_price']);
    $eventTags = $conn->real_escape_string($_POST['event_tags']);
    $eventDescription = isset($_POST['event_description']) ? $conn->real_escape_string($_POST['event_description']) : '';
    $eventPlaces = $conn->real_escape_string($_POST['event_places']);

    // Gérer l'upload de l'image
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $fileName = $_FILES['event_image']['name'];
        $fileTmpPath = $_FILES['event_image']['tmp_name'];
        $fileType = $_FILES['event_image']['type'];

        // Spécifier le répertoire de téléchargement
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Générer un nom unique pour le fichier téléchargé
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;

        // Déplacer le fichier vers l'emplacement désiré
        if (move_uploaded_file($fileTmpPath, $uploadDir . $newFileName)) {
            $filePath = $uploadDir . $newFileName;
        } else {
            $filePath = null;
        }
    } else {
        $filePath = null;
    }

    // Insérer l'événement dans la base de données
    $sql = "INSERT INTO events (event_name, event_adress, event_date, event_price, event_tags, event_description, event_image, places_available) 
            VALUES ('$eventName', '$eventAdress', '$eventDate', '$eventPrice', '$eventTags', '$eventDescription', '$filePath', '$eventPlaces')";

    if ($conn->query($sql) === TRUE) {
        $message = "Événement ajouté avec succès !";
    } else {
        $message = "Erreur : " . $conn->error;
    }
}

// Récupérer tous les événements de la base de données
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Fermer la connexion
$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord organisateur</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .dashboard-container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

    .event-form {
      display: flex;
      flex-direction: column;
    }

    .event-form label {
      margin: 10px 0 5px;
      display: block;
    }

    .event-form input, .event-form textarea, .event-form select {
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 4px;
      border: 1px solid #ccc;
      width: 100%;
    }

    .event-form button {
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .event-form button:hover {
      background-color: #45a049;
    }

    .your-events ul {
      list-style-type: none;
      padding: 0;
    }

    .your-events li {
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .your-events h2 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h1>Tableau de bord organisateur</h1>

    <!-- Formulaire d'ajout d'événement -->
    <div class="event-form">
      <h2>Créer un événement</h2>
      <form id="eventForm" action="dashboard.php" method="POST" enctype="multipart/form-data">
        <label for="eventName">Nom de l'événement :</label>
        <input type="text" id="eventName" name="event_name" required>

        <label for="eventAdress">Lieu de l'événement :</label>
        <input type="text" id="eventAdress" name="event_adress" required>

        <label for="eventDate">Date de l'événement :</label>
        <input type="date" id="eventDate" name="event_date" required>

        <label for="eventPrice">Prix de l'événement :</label>
        <input type="number" id="eventPrice" name="event_price" step="0.01" required>

        <label for="eventTags">Type d'événement :</label>
        <select id="eventTags" name="event_tags">
          <option value="Concert">Concert</option>
          <option value="Festival">Festival</option>
          <option value="Soirée">Soirée</option>
        </select>

        <label for="eventDescription">Description de l'événement :</label>
        <textarea id="eventDescription" name="event_description" required></textarea>

        <label for="eventImage">Image de l'événement :</label>
        <input type="file" id="eventImage" name="event_image" accept="image/*">

        <label for="eventPlaces">Nombre de places disponibles :</label>
        <input type="number" id="eventPlaces" name="event_places" required>

        <button type="submit">Publier l'événement</button>
      </form>
    </div>

    <!-- Afficher le message après soumission -->
    <?php if (isset($message)): ?>
      <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Afficher les événements -->
    <div class="your-events">
      <h2>Vos événements</h2>
      <ul id="eventList">
        <?php if (count($events) > 0): ?>
          <?php foreach ($events as $event): ?>
            <li>
              <a href="test.php" class="event-card">
                <?php if ($event['event_image']): ?>
                  <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Image de l'événement" class="event-image" width="100">
                <?php endif; ?>
                <div class="event-content">
                  <h3 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                  <p class="event-venue"><?php echo htmlspecialchars($event['event_adress']); ?></p>
                  <div class="event-details">
                    <span><?php echo htmlspecialchars($event['event_date']); ?></span>
                    <span><?php echo htmlspecialchars($event['event_price']); ?>€</span>
                    <span><?php echo htmlspecialchars($event['places_available']); ?> places</span>
                  </div>
                  <div class="event-tags">
                    <span class="tag"><?php echo htmlspecialchars($event['event_tags']); ?></span>
                  </div>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>Aucun événement trouvé.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</body>
</html>
