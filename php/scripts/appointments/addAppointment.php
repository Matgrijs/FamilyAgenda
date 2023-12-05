<?php
require_once('../dbConfig.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Insert appointment into Appointments table
    $sqlAppointment = "INSERT INTO Appointments (Title, Date, Description) VALUES ('$title', '$date', '$description')";

    if ($conn->query($sqlAppointment) === TRUE) {
        // Get the last inserted appointment ID
        $appointmentID = $conn->insert_id;

        // Retrieve FamilyMemberId from the session
        $familyMemberId = $_SESSION['FamilyMemberId'] ?? null;

        // Check if the session's FamilyMemberId is not already in $_POST['familyMembers']
        if ($_POST['familyMembers'] === null && $familyMemberId !== null) {
            // Insert the session's FamilyMemberId into $_POST['familyMembers']
            $_POST['familyMembers'][] = $familyMemberId;
        }

        // Insert relationship for each selected family member
        if (isset($_POST['familyMembers']) && is_array($_POST['familyMembers'])) {
            foreach ($_POST['familyMembers'] as $familyMemberID) {
                $memberAppointment = "INSERT INTO FamilyMembers_Appointments (FamilyMemberID, AppointmentID) VALUES ('$familyMemberID', '$appointmentID')";
                $conn->query($memberAppointment); // Execute the query for each selected family member
            }
        }

        header("Location: ../../../screens/dashboard.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
