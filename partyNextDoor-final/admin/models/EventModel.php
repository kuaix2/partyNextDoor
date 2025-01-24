<?php
class EventModel {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Get all events ordered by date
    public function getAllEvents() {
        $sql = "SELECT * FROM events ORDER BY event_date ASC";
        $result = $this->conn->query($sql);
        
        $events = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }
        return $events;
    }
    
    // Delete an event by ID
    public function deleteEvent($id) {
        $id = intval($id); // Ensure integer value
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        $success = $stmt->execute();
        $stmt->close();
        
        return $success;
    }
} 