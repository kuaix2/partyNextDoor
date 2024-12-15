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

// Fetch events from the database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <style>
        /* Basic styles */
        .event-card {
            display: block;
            text-decoration: none;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-image {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .event-content {
            padding-top: 15px;
        }

        .event-title {
            font-size: 1.5em;
            margin: 10px 0;
        }

        .event-venue {
            font-size: 1.2em;
            color: #555;
        }

        .event-details span {
            display: inline-block;
            margin-right: 15px;
            font-size: 1.1em;
            color: #333;
        }

        .event-tags .tag {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="events-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Check if the event image exists and is accessible
                $imagePath = 'uploads/' . $row['event_image'];
                if (!empty($row['event_image']) && file_exists($imagePath)) {
                    $imageSrc = $imagePath;
                } else {
                    $imageSrc = 'uploads/placeholder.jpg'; // Fallback image if the event image is not available
                }

                // Display each event dynamically
                echo '<a href="test.php" class="event-card">';
                echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="' . htmlspecialchars($row['event_name']) . '" class="event-image">';
                echo '<div class="event-content">';
                echo '<h3 class="event-title">' . htmlspecialchars($row['event_name']) . '</h3>';
                echo '<p class="event-venue">' . htmlspecialchars($row['event_adress']) . '</p>';
                echo '<div class="event-details">';
                echo '<span>' . htmlspecialchars($row['event_date']) . '</span>';
                echo '<span>' . htmlspecialchars($row['event_price']) . '€</span>';
                echo '</div>';
                echo '<div class="event-tags">';
                // Convert tags into individual tags
                $tags = explode(',', $row['event_tags']);
                foreach ($tags as $tag) {
                    echo '<span class="tag">' . trim($tag) . '</span>';
                }
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
