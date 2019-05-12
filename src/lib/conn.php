<?php
$servername = "localhost";
$username = "master";
$password = "D1g1talTw1n";
$dbname = "digital-twin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


