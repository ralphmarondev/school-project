<?php
session_start();
include '../../src/connection.php';
date_default_timezone_set('Asia/Manila');$d = date('Y-m-d H:i:s');

$unit = $_SESSION['department'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = '../../uploads/';
    $file_path = '';
    $upload_success = false;

    $section = mysqli_real_escape_string($mysqli, $_POST['section'] ?? '');
    $department = mysqli_real_escape_string($mysqli, $_POST['department'] ?? '');
    $area = mysqli_real_escape_string($mysqli, $_POST['area'] ?? '');
    $file_type = $_POST['file_type'] ?? '';

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $file_mime = $_FILES['file']['type'];
        $allowed_pdf = ['application/pdf'];
        $allowed_video = ['video/mp4', 'video/webm', 'video/ogg'];

        if ($file_type === 'pdf' && !in_array($file_mime, $allowed_pdf)) {
            echo json_encode(['success' => '0', 'error' => 'Invalid PDF file']);
            exit;
        }

        if ($file_type === 'video' && !in_array($file_mime, $allowed_video)) {
            echo json_encode(['success' => '0', 'error' => 'Invalid video file']);
            exit;
        }

        // Append timestamp
        $timestamp = time();
        $file_name_with_timestamp = $timestamp . '_' . $file_name;
        $target_file = $upload_dir . $file_name_with_timestamp;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $file_path = $target_file;
            $upload_success = true;
        }
    }

    if ($upload_success) {
        $sql = "INSERT INTO files (path, section, area, department,datetime_update) VALUES ('$file_path', '$section', '$area', '$department', '$d')";
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
