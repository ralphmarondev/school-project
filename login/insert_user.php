<?php
header('Content-Type: application/json');
include "../src/connection.php";

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim($mysqli->real_escape_string($_POST['username'] ?? ''));
    $password   = trim($_POST['password'] ?? '');
    $department = trim($mysqli->real_escape_string($_POST['department'] ?? ''));

    if (empty($username) || empty($password)) {
        $response['message'] = "Username and password are required.";
    } else {
        $check_sql = "SELECT * FROM users WHERE username='$username'";
        $check_result = $mysqli->query($check_sql);

        if ($check_result->num_rows > 0) {
            $response['message'] = "Username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (username, password, department)
                           VALUES ('$username', '$hashed_password', '$department')";

            if ($mysqli->query($insert_sql)) {
                $response['success'] = true;
                $response['message'] = "Account successfully created.";
            } else {
                $response['message'] = "Database error: " . $mysqli->error;
            }
        }
    }
} else {
    $response['message'] = "Invalid request method. Use POST.";
}

$mysqli->close();
echo json_encode($response);
