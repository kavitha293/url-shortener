// redirect.php

<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ust');

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Parse the shortened URL to extract the minified code
$shortened_url = $_SERVER['REQUEST_URI'];
$parts = explode('/', $shortened_url);
$minified_code = end($parts);

// Look up the original URL in the database
$sql = "SELECT long_url FROM urls WHERE short_code = '$minified_code'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// Redirect to the original URL
	$row = $result->fetch_assoc();
	$original_url = $row["long_url"];
	header("Location: $original_url");
	exit();
} else {
	// Original URL not found, display an error page
	http_response_code(404);
	echo "Error: Original URL not found";
}

// Close database connection
$conn->close();
?>
