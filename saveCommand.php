<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "motor";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['command'])) {
  $command = $conn->real_escape_string($_GET['command']);
  $sql = "INSERT INTO commands (command) VALUES ('$command')";
  if ($conn->query($sql) === TRUE) {
    echo "Saved: $command";
  } else {
    echo "Error: " . $conn->error;
  }
}

$conn->close();
?>
