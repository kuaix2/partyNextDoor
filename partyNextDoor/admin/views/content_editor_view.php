<?php
class ContentEditorView {
    public function render($contents = [], $message = '') {
        ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Plusieurs Pages</title>
    <link rel="stylesheet" href="assets/css/content_editor.css">
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
    <div class="content-editor">
        <h1>Modifier Plusieurs Pages</h1>

        <?php if (!empty($message)): ?>
            <p class="message success"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <?php foreach ($contents as $page_id => $data): ?>
                <div class="editor-section">
                    <h2><?php echo htmlspecialchars($data['name']); ?></h2>
                    <textarea id="editor-<?php echo $page_id; ?>" class="editor" name="<?php echo $page_id; ?>"><?php echo htmlspecialchars($data['content']); ?></textarea>
                </div>
            <?php endforeach; ?>
            <div class="button-group">
                <button type="submit" class="save-btn">Enregistrer toutes les modifications</button>
                <button type="button" class="back-btn" onclick="window.location.href='admin_page.php';">Retour à la page d'administration</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
        return ob_get_clean();
    }
} 