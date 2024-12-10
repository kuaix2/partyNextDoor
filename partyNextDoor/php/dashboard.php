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
    $eventDate = $conn->real_escape_string($_POST['event_date']);
    $eventDescription = $conn->real_escape_string($_POST['event_description']);
    
    // File upload
    $eventImage = '';
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['event_image']['name']);

        // Create uploads directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $uploadFile)) {
            $eventImage = $uploadFile;  // Store the path to the image
        } else {
            $message = "Failed to upload image.";
        }
    }

    // Insert event into the database
    $sql = "INSERT INTO events (event_name, event_date, event_description, event_image) 
            VALUES ('$eventName', '$eventDate', '$eventDescription', '$eventImage')";

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

        button {
            padding: 10px;
            background-color: #ff8000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        button:hover {
            background-color: #ff2020;
        }

        .event-form {
            display: flex;
            flex-direction: column;
        }

        .event-form label {
            margin: 10px 0 5px;
        }

        .event-form input, .event-form textarea {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .event-form button {
            padding: 10px;
            background-color: #ff8000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .event-form button:hover {
            background-color: #ff2020;
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
        
        <!-- Host an Event Button -->
        <button id="hostEventButton">Host an Event</button>
        
        <!-- Event Form (Initially Hidden) -->
        <div class="event-form" id="eventFormContainer" style="display: none;">
            <h2>Host an Event</h2>
            <form id="eventForm" action="dashboard.php" method="POST" enctype="multipart/form-data">
                <label for="eventName">Event Name:</label>
                <input type="text" id="eventName" name="event_name" required>
                
                <label for="eventDate">Event Date:</label>
                <input type="date" id="eventDate" name="event_date" required>
                
                <label for="eventDescription">Event Description:</label>
                <textarea id="eventDescription" name="event_description" required></textarea>
                
                <label for="eventImage">Event Image:</label>
                <input type="file" id="eventImage" name="event_image" accept="image/*">
                
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
                            <strong><?php echo htmlspecialchars($event['event_name']); ?></strong><br>
                            <?php echo htmlspecialchars($event['event_date']); ?><br>
                            <em><?php echo htmlspecialchars($event['event_description']); ?></em><br>
                            <?php if (!empty($event['event_image'])): ?>
                              <img src="/uploads/<?php echo basename($event['event_image']); ?>" alt="Event Image" width="100">

                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No events found.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script>
        // JavaScript to handle form visibility
        document.getElementById('hostEventButton').addEventListener('click', function() {
            document.getElementById('eventFormContainer').style.display = 'block';
        });
    </script>
</body>
</html>
