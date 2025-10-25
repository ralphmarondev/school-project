<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Logging Out...</title>
	<script>
		// Clear the saved email/username from localStorage
		localStorage.removeItem('email');

		// Redirect to the login page after clearing
		window.location.href = "../login/";
	</script>
</head>

<body>
</body>

</html>