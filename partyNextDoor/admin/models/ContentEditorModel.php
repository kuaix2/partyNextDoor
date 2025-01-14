<?php
class ContentEditorModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getAllPages() {
        $contents = [];
        $sql = "SELECT page_id, page_name, content FROM multiple_content";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contents[$row['page_id']] = [
                    'name' => $row['page_name'],
                    'content' => $row['content']
                ];
            }
        }
        return $contents;
    }
    
    public function updatePageContent($page_id, $content) {
        $sql = "UPDATE multiple_content SET content = ? WHERE page_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $content, $page_id);
        
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
} 