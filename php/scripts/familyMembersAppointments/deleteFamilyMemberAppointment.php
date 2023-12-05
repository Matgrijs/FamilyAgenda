<?php
require_once('../dbConfig.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = file_get_contents('php://input');
    $requestData = json_decode($postData, true);

    $AppointmentID = $requestData['appointmentID'];

    // Delete the record from FamilyMembers_Appointments based on AppointmentID
    $sql = "DELETE FROM FamilyMembers_Appointments WHERE AppointmentID = '$AppointmentID'";
    $result = $conn->query($sql);

    if ($result !== TRUE) {
        // If deletion failed
        echo '<script>alert("Verwijderen mislukt, probeer het alsjeblieft opnieuw."); window.location.href = "../../index.html";</script>';
    }
}
?>
