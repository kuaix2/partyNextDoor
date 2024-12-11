<?php
$host = 'localhost';
$dbname = 'bddpartynextdoor';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$content = '';

// If the form is submitted, update the content
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated content from the form
    $updated_content = $_POST['content'];

    // Update the content in the database
    $sql = "UPDATE content SET content = ? WHERE id = 1";  // Assuming the ID is 1
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $updated_content);

    if ($stmt->execute()) {
        $message = "Content updated successfully!";
    } else {
        $message = "Error updating content: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch the existing content from the database
$sql = "SELECT content FROM content WHERE id = 1";  // Assuming you're fetching content with ID = 1
$result = $conn->query($sql);

// Default empty content if no data found
if ($result->num_rows > 0) {
    // Fetch the content from the database
    $row = $result->fetch_assoc();
    $content = $row['content'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <script src="https://cdn.tiny.cloud/1/pn2zh1v0rk8aaco8a825efyux308vo1pt0rmkw3f9i1g5z0i/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#editor',  // Apply TinyMCE to the entire content textarea
            plugins: 'advlist autolink lists link image charmap print preview anchor help',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | help',
            height: 500,  // Adjust height to fit the entire document
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave(); // Ensure content is saved into the textarea when editor changes
                });
            }
        });
    </script>
</head>
<body>

    <h1>Edit Content</h1>

    <!-- Show message if the content was updated -->
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Form to submit the updated content -->
    <form action="" method="POST">
        <textarea id="editor" name="content" rows="10" cols="30"><?php echo htmlspecialchars($content); ?></textarea>
        <button type="submit">Save Changes</button>
    </form>

</body>
</html>
