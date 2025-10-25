<?php
session_start();
include "../src/connection.php";

$login_error = '';
$login_success = false;
$register_success = false;
$redirect_url = '../user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = trim($mysqli->real_escape_string($_POST['username']));
        $password = trim($_POST['password']);

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['department'] = $row['department'] ?? '';
                echo "<script>
                    localStorage.setItem('email', '$username');
                    window.location.href = '$redirect_url';
                    </script>";
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "No user found with this username.";
        }
    } elseif (isset($_POST['register'])) {
        $username = trim($mysqli->real_escape_string($_POST['username']));
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $login_error = "Please enter both username and password.";
        } else {
            $check = $mysqli->query("SELECT * FROM users WHERE username='$username'");
            if ($check->num_rows > 0) {
                $login_error = "Username already exists. Try another.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $mysqli->query("INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')");
                if ($insert) {
                    $register_success = true;
                } else {
                    $login_error = "Registration failed: " . $mysqli->error;
                }
            }
        }
    }
}

$mysqli->close();

if ($login_success) {
    header("Location: $redirect_url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="C-Trike Predictive Maintenance Login Page">
    <title>Login</title>

    <link href="https://demo.adminkit.io/css/light.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: rgb(174, 14, 14);
            --primary-light: rgb(220, 60, 60);
            --text: #222;
        }

        body {
            background: #fff;
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <main class="d-flex w-100 mt-1">
        <div class="container d-flex flex-column">
            <div class="row">
                <div class="col-11 col-lg-10 text-center mx-auto mt-1">
                    <img src="../assets/emob_logo_f.png" alt="" style="height: 100px; width: 100px;">
                    <h3>C-Trike Predictive Maintenance and Dashboard</h3>
                </div>

                <div class="col-sm-10 col-md-5 col-xl-4 mx-auto d-table">
                    <div class="d-table-cell align-middle">
                        <div class="text-center">
                            <h3>Welcome Back!</h3>
                            <p class="lead">Log in to your account to continue</p>
                        </div>

                        <div class="card border shadow">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <?php if (!empty($login_error)) : ?>
                                        <div class="alert alert-danger p-2 mb-3" role="alert">
                                            <?php echo $login_error; ?>
                                        </div>
                                    <?php elseif ($register_success) : ?>
                                        <div class="alert alert-success p-2 mb-3" role="alert">
                                            Successfully registered.
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Username</label>
                                            <input class="form-control form-control-lg p-3" type="text" name="username" placeholder="Enter username" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Password</label>
                                            <input class="form-control form-control-lg p-3" type="password" name="password" placeholder="Enter password" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" name="login" class="btn btn-primary p-2">Log in</button>
                                            <button type="submit" name="register" class="btn btn-secondary p-2">Register</button>
                                            <p style="color: #001efdff; text-align: center; font-size: 0.8em;">Forgot Password?</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>