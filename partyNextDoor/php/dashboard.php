<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddpartynextdoor";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add the event to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_name'])) {
    // Sanitize inputs
    $eventName = $conn->real_escape_string($_POST['event_name']);
    $eventVenue = $conn->real_escape_string($_POST['event_venue']);
    $eventDate = $conn->real_escape_string($_POST['event_date']);
    $eventPrice = $conn->real_escape_string($_POST['event_price']);
    $eventTags = $conn->real_escape_string($_POST['event_tags']);
    $eventDescription = isset($_POST['event_description']) ? $conn->real_escape_string($_POST['event_description']) : '';  // Make sure event_description is present
    $eventPlaces = $conn->real_escape_string($_POST['event_places']);  // Get number of places

    // Handle image upload
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $fileName = $_FILES['event_image']['name'];
        $fileTmpPath = $_FILES['event_image']['tmp_name'];
        $fileType = $_FILES['event_image']['type'];

        // Specify the upload directory
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique name for the uploaded file
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;

        // Move the file to the desired location
        if (move_uploaded_file($fileTmpPath, $uploadDir . $newFileName)) {
            // File was uploaded successfully
            $filePath = $uploadDir . $newFileName;
        } else {
            $filePath = null;
        }
    } else {
        $filePath = null;
    }

    // Insert event into the database
    $sql = "INSERT INTO events (event_name, event_venue, event_date, event_price, event_tags, event_description, event_image, places_available) 
            VALUES ('$eventName', '$eventVenue', '$eventDate', '$eventPrice', '$eventTags', '$eventDescription', '$filePath', '$eventPlaces')";

    if ($conn->query($sql) === TRUE) {
        $message = "Event added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all events from the database
$sql = "SELECT * FROM events ORDER BY event_date DESC";
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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Dashboard</title>
  <style>
    /* styles.css */
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

    .event-form input, .event-form textarea {
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
    <h1>Organizer Dashboard</h1>

    <!-- Event Form -->
    <div class="event-form">
      <h2>Host an Event</h2>
      <form id="eventForm" action="dashboard.php" method="POST" enctype="multipart/form-data">
    <label for="eventName">Event Name:</label>
    <input type="text" id="eventName" name="event_name" required>

    <label for="eventVenue">Event Venue:</label>
    <input type="text" id="eventVenue" name="event_venue" required>

    <label for="eventDate">Event Date:</label>
    <input type="date" id="eventDate" name="event_date" required>

    <label for="eventPrice">Event Price:</label>
    <input type="number" id="eventPrice" name="event_price" step="0.01" required>

    <label for="eventTags">Event Type:</label>
    <select id="eventTags" name="event_tags">
        <option value="Concert">Concert</option>
        <option value="Festival">Festival</option>
        <option value="Soirée">Soirée</option>
    </select>

    <label for="eventDescription">Event Description:</label>
    <textarea id="eventDescription" name="event_description" required></textarea>

    <label for="eventImage">Event Image:</label>
    <input type="file" id="eventImage" name="event_image" accept="image/*">

    <label for="eventPlaces">Number of Places Available:</label>
    <input type="number" id="eventPlaces" name="event_places" required>

    <button type="submit">Publish Event</button>
</form>

    </div>

    <!-- Display Message after Event Submission -->
    <?php if (isset($message)): ?>
      <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Display Your Events -->
    <div class="your-events">
      <h2>Your Events</h2>
      <ul id="eventList">
        <?php if (count($events) > 0): ?>
          <?php foreach ($events as $event): ?>
            <li>
              <a href="test.php" class="event-card">
                <?php if ($event['event_image']): ?>
                  <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="Event Image" class="event-image" width="100">
                <?php endif; ?>
                <div class="event-content">
                  <h3 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                  <p class="event-venue"><?php echo htmlspecialchars($event['event_venue']); ?></p>
                  <div class="event-details">
                    <span><?php echo htmlspecialchars($event['event_date']); ?></span>
                    <span><?php echo htmlspecialchars($event['event_price']); ?>€</span>
                    <span><?php echo htmlspecialchars($event['places_available']); ?></span>
                  </div>
                  <div class="event-tags">
                    <span class="tag"><?php echo htmlspecialchars($event['event_tags']); ?></span>
                  </div>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>No events found.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</body>
</html>
