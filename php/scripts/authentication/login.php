<?php
session_start();
require_once('../dbConfig.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM FamilyMembers WHERE Email='$email' AND Password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // put the FamilyMemberID in the session to use it in other queries
    $_SESSION['FamilyMemberId'] = $row['FamilyMemberID'];
    header("Location: ../../../screens/dashboard.html");
    exit();
  } else {
    echo '<script>alert("Gebruiker niet gevonden"); window.location.href = "../../../index.html";</script>';
  }
}
?>
