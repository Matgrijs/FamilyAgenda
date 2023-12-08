<?php
require_once('../dbConfig.php');
    class Appointment {
        private $conn;

        public function __construct($dbConnection) {
            $this->conn = $dbConnection;
            session_start();
        }

        public function createAppointment($title, $date, $description, $familyMembers) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $sqlAppointment = "INSERT INTO Appointments (Title, Date, Description) VALUES ('$title', '$date', '$description')";

                if ($this->conn->query($sqlAppointment) === TRUE) {
                    $appointmentID = $this->conn->insert_id;
                    $familyMemberId = $_SESSION['FamilyMemberId'] ?? null;

                    if ($familyMembers === null && $familyMemberId !== null) {
                        $familyMembers[] = $familyMemberId;
                    }

                    if (is_array($familyMembers)) {
                        foreach ($familyMembers as $familyMemberID) {
                            $memberAppointment = "INSERT INTO FamilyMembers_Appointments (FamilyMemberID, AppointmentID) VALUES ('$familyMemberID', '$appointmentID')";
                            $this->conn->query($memberAppointment);
                        }
                    }

                    header("Location: ../../../screens/dashboard.html");
                    exit();
                } else {
                    echo "Error: " . $this->conn->error;
                }
            }
        }

        public function checkAppointment($familyMemberID, $appointmentID) {
            $sql = "SELECT * FROM FamilyMembers_Appointments WHERE FamilyMemberID = '$familyMemberID' AND AppointmentID = '$appointmentID'";
            $result = $this->conn->query($sql);
            $hasAppointment = $result->num_rows > 0 ? true : false;
            return ['hasAppointment' => $hasAppointment];
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST['action'] === 'create') {
            $title = $_POST['title'];
            $date = $_POST['date'];
            $description = $_POST['description'];
            $familyMembers = $_POST['familyMembers'];
    
            $appointment = new Appointment($conn);
            $registrationResult = $appointment->createAppointment($title, $date, $description, $familyMembers);
    
            if ($registrationResult) {
                header("Location: ../../../screens/dashboard.html");
                exit();
            } else {
                echo '<script>alert("Aanmaken van de afspraak is fout gegaan, probeer het alsjeblieft opnieuw."); window.location.href = "../../../screens/addAppointment.html";</script>';
            }
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $familyMemberID = $_GET['familyMemberID']; // Contains the value 123
        $appointmentID = $_GET['appointmentID']; // Contains the value 456

        $appointment = new Appointment($conn);
        $response = $appointment->checkAppointment($familyMemberID, $appointmentID);

        echo json_encode($response);
    }
?>