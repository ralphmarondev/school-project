<?php
header('Content-Type: application/json');
include "../src/connection.php";

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $username   = $mysqli->real_escape_string($_GET['username'] ?? '');
    $password   = $_GET['password'] ?? '';
    $department = $mysqli->real_escape_string($_GET['department'] ?? '');

    if (empty($username) || empty($password)) {
        $response['message'] = "Username and password are required.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT * FROM users WHERE username='$username'";
        $check_result = $mysqli->query($check_sql);

        if ($check_result->num_rows > 0) {
            $response['message'] = "Username already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
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
    $response['message'] = "Invalid request method. Use GET.";
}

$mysqli->close();
echo json_encode($response);
