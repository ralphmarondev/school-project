<?php
include '../../src/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if URL parameter exists
    if (!isset($_GET['url'])) {
        echo json_encode(['success' => false, 'error' => 'URL parameter missing']);
        exit;
    }

    // Sanitize and retrieve URL
    $file_url = mysqli_real_escape_string($mysqli, $_GET['url']);
  
    // Delete file from filesystem
    if (unlink(''.$file_url)) {
        // Delete record from database
        $delete_sql = "DELETE FROM files WHERE path = '$file_url'";
        if ($mysqli->query($delete_sql)) {
            echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database delete operation failed']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete file from filesystem']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
