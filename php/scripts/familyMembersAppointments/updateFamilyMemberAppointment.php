<?php
require_once('../dbConfig.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $postData = file_get_contents('php://input');

  // Decode the JSON data if it's in JSON format
  $requestData = json_decode($postData, true); // Decode as associative array

  $AppointmentID = $requestData['appointmentID'];
  $FamilyMemberID = $requestData['familyMemberId'];

  $sql = "INSERT INTO FamilyMembers_Appointments (FamilyMemberID, AppointmentID) VALUES ('$FamilyMemberID', '$AppointmentID')";
  $conn->query($sql);
} else {
  echo "Error: " . $conn->error;
}
?>
