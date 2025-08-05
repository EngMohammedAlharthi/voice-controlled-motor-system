<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "motor";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT command FROM commands ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo $row['command'];
} else {
  echo "None";
}

$conn->close();
?>
