<?php

session_start();


if (!isset($_SESSION['user_id'])) {
    
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];  


include 'database/db_conn.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_name'])) {
    
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $eventAdresse = $conn->real_escape_string($_POST['event_adresse']);
    $eventDate = $conn->real_escape_string($_POST['event_date']);
    $eventPrice = $conn->real_escape_string($_POST['event_price']);
    $eventTags = $conn->real_escape_string($_POST['event_tags']);
    $eventDescription = isset($_POST['event_description']) ? $conn->real_escape_string($_POST['event_description']) : '';
    $eventPlaces = $conn->real_escape_string($_POST['event_places']);

    
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $fileName = $_FILES['event_image']['name'];
        $fileTmpPath = $_FILES['event_image']['tmp_name'];
        $fileType = $_FILES['event_image']['type'];

        
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

       
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;

       
        if (move_uploaded_file($fileTmpPath, $uploadDir . $newFileName)) {
            $filePath = $uploadDir . $newFileName;
        } else {
            $filePath = null;
        }
    } else {
        $filePath = null;
    }


$user_id = $_SESSION['user_id'];  


$sql = "INSERT INTO events (event_name, event_adresse, event_date, event_price, event_tags, event_description, event_image, places_available, user_id) 
        VALUES ('$eventName', '$eventAdresse', '$eventDate', '$eventPrice', '$eventTags', '$eventDescription', '$filePath', '$eventPlaces', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        $message = "Événement ajouté avec succès !";
    } else {
        $message = "Erreur : " . $conn->error;
    }
}


$sql = "SELECT * FROM events WHERE user_id = '$user_id' ORDER BY event_date DESC";
$result = $conn->query($sql);
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
  


$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord organisateur</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>

<?php 
include 'navigation/header.php';
?>

  <div class="dashboard-container">
    <h1>Tableau de bord organisateur</h1>

    
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

    
    <?php if (isset($message)): ?>
      <p><?php echo $message; ?></p>
    <?php endif; ?>

   
    <div class="your-events">
      <h2>Vos événements</h2>
      <ul id="eventList">
        <?php if (count($events) > 0): ?>
          <?php foreach ($events as $event): ?>
            <li>
                <a href="fiche-evenement.php?id=<?php echo $event['id']; ?>" class="event-card">
                <?php if ($event['event_image']): ?>
                    <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Événement <?php echo htmlspecialchars($event['event_name']); ?>" class="event-image">
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

<?php 
include 'navigation/footer.php';
?>

</html>
