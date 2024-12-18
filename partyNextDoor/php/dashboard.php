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
    $eventAdresse = $conn->real_escape_string($_POST['event_adresse']);
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
    $sql = "INSERT INTO events (event_name, event_adresse, event_date, event_price, event_tags, event_description, event_image, places_available) 
            VALUES ('$eventName', '$eventAdresse', '$eventDate', '$eventPrice', '$eventTags', '$eventDescription', '$filePath', '$eventPlaces')";


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
      margin: 30px auto;
      padding: 40px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #4CAF50;
      font-size: 36px;
      margin-bottom: 40px; /* Espacement plus grand entre le titre et le formulaire */
    }

    .event-form {
      display: flex;
      flex-direction: column;
      gap: 30px; /* Augmentation de l'espace entre les champs */
    }

    .event-form label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .event-form input, .event-form textarea, .event-form select {
      padding: 12px;
      border: 2px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
      width: 100%;
      box-sizing: border-box;
    }

    .event-form input[type="file"] {
      padding: 10px;
    }

    .event-form button {
      background-color: #4CAF50;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 18px;
      transition: background-color 0.3s ease;
      margin-top: 20px; /* Ajouter de l'espace au-dessus du bouton */
    }

    .event-form button:hover {
      background-color: #45a049;
    }

    /* Ajuster les espacements dans la liste des événements */
    .your-events ul {
      list-style-type: none;
      padding: 0;
    }

    .your-events li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      background-color: #f9f9f9;
      border-radius: 8px;
      margin-bottom: 20px; /* Espacement entre les événements */
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .your-events li:hover {
      transform: scale(1.02);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .event-card {
      display: flex;
      text-decoration: none;
      color: inherit;
      width: 100%;
    }

    .event-image {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 6px;
      margin-right: 20px;
    }

    .event-content {
      flex-grow: 1;
    }

    .event-title {
      font-size: 20px;
      font-weight: bold;
      color: #333;
      margin: 0;
      padding-bottom: 5px;
    }

    .event-venue {
      font-size: 16px;
      color: #777;
      margin: 5px 0;
    }

    .event-details {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      color: #555;
      margin-top: 10px;
    }

    .event-details span {
      margin-right: 15px;
    }

    .event-tags {
      margin-top: 10px;
    }

    .tag {
      display: inline-block;
      background-color: #4CAF50;
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 14px;
    }

    .event-form textarea {
      height: 150px;
      resize: vertical;
    }

    .message {
      text-align: center;
      margin-top: 20px;
      font-size: 18px;
      color: #4CAF50;
    }

    /* Ajuster les espacements dans le formulaire */
    .event-form input,
    .event-form select,
    .event-form textarea,
    .event-form button {
      margin-bottom: 20px; /* Ajout d'un espacement en bas de chaque élément */
    }

    @media (max-width: 768px) {
      .dashboard-container {
        padding: 20px;
        margin: 20px;
      }

      .event-form input,
      .event-form select,
      .event-form textarea {
        font-size: 14px;
      }

      .your-events li {
        flex-direction: column;
        align-items: flex-start;
      }

      .event-image {
        margin-bottom: 10px;
        width: 100%;
        height: auto;
      }

      .event-details {
        flex-direction: column;
        align-items: flex-start;
      }

      .event-details span {
        margin-bottom: 8px;
      }
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
        <input type="text" id="eventAdress" name="event_adresse" required>

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
                  <p class="event-venue"><?php echo htmlspecialchars($event['event_adresse']); ?></p>
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
