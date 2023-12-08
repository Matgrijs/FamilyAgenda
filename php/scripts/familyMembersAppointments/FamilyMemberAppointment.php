<?php
require_once('../dbConfig.php');

class FamilyMemberAppointment {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function deleteAppointment($familyMemberID, $appointmentID) {
        $sql = "DELETE FROM FamilyMembers_Appointments WHERE AppointmentID = '$appointmentID' and FamilyMemberID = '$familyMemberID'";
        $result = $this->conn->query($sql);

        return $result;
    }

    public function getAllAppointments() {
        $sql = "SELECT Appointments.*, FamilyMembers.*
                FROM Appointments
                INNER JOIN FamilyMembers_Appointments
                ON Appointments.AppointmentID = FamilyMembers_Appointments.AppointmentID
                INNER JOIN FamilyMembers
                ON FamilyMembers_Appointments.FamilyMemberID = FamilyMembers.FamilyMemberID";

        $result = $this->conn->query($sql);

        $appointments = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                unset($row['Password']); // Remove Password from familyMember
                $appointments[] = $row;
            }
        }

        return $appointments;
    }

    public function linkAppointmentToFamilyMember($familyMemberID, $appointmentID) {
        $sql = "INSERT INTO FamilyMembers_Appointments (FamilyMemberID, AppointmentID) VALUES ('$familyMemberID', '$appointmentID')";
        $result = $this->conn->query($sql);

        return $result;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = file_get_contents('php://input');
    $requestData = json_decode($postData, true);

    $appointmentManager = new FamilyMemberAppointment($conn);


    if (isset($requestData['familyMemberId']) && isset($requestData['appointmentID'])) {
        $familyMemberID = $requestData['familyMemberId'];
        $appointmentID = $requestData['appointmentID'];
        $appointmentManager->linkAppointmentToFamilyMember($familyMemberID, $appointmentID);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $appointmentManager = new FamilyMemberAppointment($conn);
    $appointments = $appointmentManager->getAllAppointments();

    header('Content-Type: application/json');
    echo json_encode($appointments);
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $postData = file_get_contents('php://input');
    $requestData = json_decode($postData, true);

    $appointmentManager = new FamilyMemberAppointment($conn);

    if (isset($requestData['familyMemberId']) && isset($requestData['appointmentID'])) {
        $familyMemberID = $requestData['familyMemberId'];
        $appointmentID = $requestData['appointmentID'];
        var_dump($requestData);
        $appointmentManager->deleteAppointment($familyMemberID, $appointmentID);
    }
}
?>
