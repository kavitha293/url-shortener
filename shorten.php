// shorten.php
<?php
// Connect to MySQL database
$conn = new mysqli('localhost', 'root', '', 'ust');

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Function to generate a random short code
function generateShortCode() {
	return substr(md5(uniqid()), 0, 6); // Change length as needed
}

// Initialize variable to store shortened URL
$shortened_url = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$long_url = $_POST["long_url"];
	$short_code = generateShortCode();

	// Insert the mapping into the database
	$sql = "INSERT INTO urls (long_url, short_code) VALUES ('$long_url', '$short_code')";
	if ($conn->query($sql) === TRUE) {
		// Construct the shortened URL
		$shortened_url = "http://localhost/UrlShorter-main/$short_code"; // Change domain as needed
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

// Close database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shortened URL</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>

<?php if (!empty($shortened_url)): ?>
	<h1>Shortened URL</h1>
	
	<p>
		<a id="shortened-url" href="<?php echo $shortened_url; ?>"><?php echo $shortened_url; ?></a>
		<br>
		<button class="copy-button" onclick="copyToClipboard()">Copy to clipboard</button>
	</p>

<?php else: ?>
	<h1>Error</h1><br>
	<p>There was an error shortening the URL.</p>
<?php endif; ?>

<script>
	function copyToClipboard() {
		var urlElement = document.getElementById("shortened-url");
		var url = urlElement.textContent;

		var tempInput = document.createElement("input");
		tempInput.setAttribute("value", url);
		document.body.appendChild(tempInput);
		tempInput.select();
		document.execCommand("copy");
		document.body.removeChild(tempInput);

		alert("URL copied to clipboard: " + url);
	}
</script>

</body>
</html>
