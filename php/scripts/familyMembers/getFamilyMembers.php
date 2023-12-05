<?php
require_once('../dbConfig.php');

// Assuming you want to fetch all family members
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Fetch data from the database
    $sql = "SELECT Name, FamilyMemberID FROM FamilyMembers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store results in an array
        $familyMembers = array();
        while ($row = $result->fetch_assoc()) {
            $familyMembers[] = $row;
        }

        // Return family members as JSON
        header('Content-Type: application/json');

        echo json_encode($familyMembers);
    } else {
        // No family members found
        echo json_encode(array('message' => 'No family members found.'));
    }
}
?>
