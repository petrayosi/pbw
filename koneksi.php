<!-- <?php
date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "";
$db = "webdailyjournal"; 


$conn = new mysqli($servername,$username,$password,$db);


if($conn->connect_error){

	die("Connection failed : ".$conn->connect_error);
}


?> -->
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webdailyjournal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute query
$sql = "SELECT * FROM article ORDER BY id DESC";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!-- Hostname:
localhost
Database:
brabsenm_webdailyjournal
Username:
brabsenm_webdailyjournal
Password:
vtAtZnEfX52qPpH6XKjK -->