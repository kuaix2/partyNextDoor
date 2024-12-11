<?php
$host = 'localhost';
$dbname = 'bddpartynextdoor';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$contents = [];

// Handle form submission to update content for multiple pages
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $page_id => $updated_content) {
        $sql = "UPDATE multiple_content SET content = ? WHERE page_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $updated_content, $page_id);

        if (!$stmt->execute()) {
            die("Error updating content for page $page_id: " . $stmt->error);
        }

        $stmt->close();
    }
    $message = "Content updated successfully!";
}

// Fetch existing content for all pages
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
    die("No pages found in the database.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Multiple Pages</title>
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

    <h1>Edit Multiple Pages</h1>

    <!-- Display success message if content was updated -->
    <?php if (isset($message)): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Form to edit multiple pages -->
    <form action="" method="POST">
        <?php foreach ($contents as $page_id => $data): ?>
            <h2><?php echo htmlspecialchars($data['name']); ?></h2>
            <textarea id="editor-<?php echo $page_id; ?>" class="editor" name="<?php echo $page_id; ?>">
                <?php echo htmlspecialchars($data['content']); ?>
            </textarea>
        <?php endforeach; ?>
        <button type="submit">Save All Changes</button>
    </form>

</body>
</html>
