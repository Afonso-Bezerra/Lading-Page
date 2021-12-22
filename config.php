<?php
$servername = "sql543.main-hosting.eu";
$username = "u784648848_dash";
$password = "pl[5R$9i";
$dbname = "u784648848_dash";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>