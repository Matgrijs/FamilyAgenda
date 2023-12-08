<?php
session_start();
require_once('../dbConfig.php');

class UserManagement {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function registerFamilyMember($name, $email, $password) {
        $stmt = $this->conn->prepare("INSERT INTO FamilyMembers (Name, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        $result = $stmt->execute();

        if ($result) {
            $lastInsertedId = $this->conn->insert_id;

            $_SESSION['FamilyMemberId'] = $lastInsertedId;
            return true;
        } else {
            return false;
        }
    }

    public function authenticateUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM FamilyMembers WHERE Email=? AND Password=?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['FamilyMemberId'] = $row['FamilyMemberID'];
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] === 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userManagement = new UserManagement($conn);
        $registrationResult = $userManagement->registerFamilyMember($name, $email, $password);

        if ($registrationResult) {
            header("Location: ../../../screens/dashboard.html");
            exit();
        } else {
            echo '<script>alert("Registreren van de gebruiker is fout gegaan, probeer het alsjeblieft opnieuw."); window.location.href = "../../../index.html";</script>';
        }
    } elseif ($_POST['action'] === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userManagement = new UserManagement($conn);
        $authenticationResult = $userManagement->authenticateUser($email, $password);

        if ($authenticationResult) {
            header("Location: ../../../screens/dashboard.html");
            exit();
        } else {
            echo '<script>alert("Gebruiker niet gevonden"); window.location.href = "../../../index.html";</script>';
        }
    }
}
?>
