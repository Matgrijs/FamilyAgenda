<?php
require_once('../dbConfig.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Assuming you receive the FamilyMemberID and AppointmentID from the URL query string
    $familyMemberID = $_GET['familyMemberID'];
    $appointmentID = $_GET['appointmentID'];

    // Check if there's an existing appointment for the user
    $sql = "SELECT * FROM FamilyMembers_Appointments WHERE FamilyMemberID = '$familyMemberID' AND AppointmentID = '$appointmentID'";
    $result = $conn->query($sql);

    // Return a boolean value indicating if the appointment exists for the user
    $hasAppointment = $result->num_rows > 0 ? true : false;

    echo json_encode(['hasAppointment' => $hasAppointment]);
}
?>
