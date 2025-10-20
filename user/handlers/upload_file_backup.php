<?php
session_start();
include '../../src/connection.php';



$unit = $_SESSION['department'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $upload_dir = '../../uploads/';
    $file_path = '';
    $upload_success = false;
    $section = isset($_POST['section']) ? mysqli_real_escape_string($mysqli, $_POST['section']) : '';
    $department = isset($_POST['department']) ? mysqli_real_escape_string($mysqli, $_POST['department']) : '';
    $area = isset($_POST['area']) ? mysqli_real_escape_string($mysqli, $_POST['area']) : '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);

        // Append timestamp to the file name
        $timestamp = time(); // Current Unix timestamp
        $file_name_with_timestamp = $timestamp . '_' . $file_name;

        $target_file = $upload_dir . $file_name_with_timestamp;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
            $upload_success = true;
        }
    }

    if ($upload_success) {
        $sql = "INSERT INTO files (path, section, area, department) VALUES ('$file_path', '$section', '$area', '$department')";
        $result = $mysqli->query($sql);
        if ($result) {
            echo json_encode(['success' => '1']);
        } else {
            echo json_encode(['success' => '0', 'error' => 'Database insert failed']);
        }
    } else {
        echo json_encode(['success' => '0', 'error' => 'File upload failed']);
    }
}
