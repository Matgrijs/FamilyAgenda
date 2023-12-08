<?php
require_once('../dbConfig.php');

class FamilyMember {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllFamilyMembers() {
        $sql = "SELECT Name, FamilyMemberID FROM FamilyMembers";
        $result = $this->conn->query($sql);

        $familyMembers = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $familyMembers[] = $row;
            }
        }

        return $familyMembers;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $FamilyMember = new FamilyMember($conn);

    $familyMembers = $FamilyMember->getAllFamilyMembers();

    header('Content-Type: application/json');
    echo json_encode($familyMembers);
}
?>
