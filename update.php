<?php
// update_status.php

// Assuming you have a database connection established in your main script
// $conn = new mysqli("your_host", "your_username", "your_password", "your_database");

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if the action parameter is set
    if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
        
        // Update the service status in the database
        if (isset($_SESSION['id'])) {
            $userid = $_SESSION['id'];
            $status = 'Inactive';
            
            $update_sql = "UPDATE tbl_questions SET status = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $status, $userid);

            if ($stmt->execute()) {
                // You can return a success message if needed
                echo json_encode(['status' => 'success', 'message' => 'Service status updated successfully']);
            } else {
                // Return an error message if the update fails
                echo json_encode(['status' => 'error', 'message' => 'Error updating service status']);
            }
        } else {
            // Return an error message if the user is not logged in
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        }
    } else {
        // Return an error message if the action parameter is missing or incorrect
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    // Return an error message if it's not a POST request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
