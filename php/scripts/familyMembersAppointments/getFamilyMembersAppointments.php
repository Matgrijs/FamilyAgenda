<?php
require_once('../dbConfig.php');

// Assuming you want to fetch all appointments for a given family member ID
if ($_SERVER["REQUEST_METHOD"] == "GET") {
 // Fetch data from the database with a JOIN query
  $sql = "SELECT Appointments.*, FamilyMembers.*
          FROM Appointments
          INNER JOIN FamilyMembers_Appointments
          ON Appointments.AppointmentID = FamilyMembers_Appointments.AppointmentID
          INNER JOIN FamilyMembers
          ON FamilyMembers_Appointments.FamilyMemberID = FamilyMembers.FamilyMemberID";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Store results in an array
    $appointments = array();
    while ($row = $result->fetch_assoc()) {
      $appointments[] = $row;
    }

    // Return appointments as JSON
    header('Content-Type: application/json');

    // Loop through the appointments array and unset the Password field from the familyMember
    foreach ($appointments as &$appointment) {
      unset($appointment['Password']);
    }

    echo json_encode($appointments);
  } else {
    // No appointments found
    echo json_encode(array('message' => 'No appointments found for this family member.'));
  }
}
?>
