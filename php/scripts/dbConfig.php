<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "familyagenda";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}
?>
